<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Bed;
use App\Models\Payment;
use App\Models\Resident;
use App\Models\Accessory;
use Illuminate\Http\Request;
use App\Models\GuestAccessory;
use App\Models\StudentAccessory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use App\Helpers\Helper;
use App\Models\Invoice;
use App\Models\InvoiceItem;



class StudentAccessoryController extends Controller
{

    public function showPaymentForm($resident_id, $student_accessory_id)
    {
        try {
            // Check if the resident exists
            $resident = Resident::findOrFail($resident_id);

            // Check if the accessory exists
            $accessory = StudentAccessory::findOrFail($student_accessory_id);

            return view('resident.make_payment', [
                'resident_id' => $resident_id,
                'accessory_id' => $student_accessory_id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Resident or Accessory not found.',
                'details' => $e->getMessage()
            ], 404);
        }
    }


    public function addAccessory(Request $request)
    {
        try {
        $resident = Helper::get_resident_details($request->header('auth-id'));
        // Validate request
        $validated = $request->validate([
            'accessory_head_id' => 'required|exists:accessory,accessory_head_id', // Ensure accessory exists
            'duration' => 'required|in:1 Month,3 Months,6 Months,1 Year' // Validate duration
        ]);

        DB::beginTransaction();

            $accessory = Accessory::where('accessory_head_id', $validated['accessory_head_id'])->with('accessoryHead')->firstOrFail();

            // Get current date
            $fromDate = now();

            // Determine the number of months for the selected duration
            $months = match ($validated['duration']) {
                '1 Month' => 1,
                '3 Months' => 3,
                '6 Months' => 6,
                '1 Year' => 12,
            };

            // Calculate `to_date` based on the duration
            $toDate = $fromDate->copy()->addMonths($months);

            // Set a fixed due date (30 days from now)
            $dueDate = now()->addDays($months * 30);

            // Calculate the total amount (price × duration in months)
            $totalAmount = $accessory->price * $months;

            $nextId = (Invoice::max('id') ?? 0) + 1;
            $invoiceNumber = 'INV-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
            //create Invoice
            $invoice = Invoice::create([
                'resident_id' => $resident->id,
                'invoice_number' => $invoiceNumber,
                'invoice_date' => now(),
                'due_date' => $dueDate,
                'total_amount' => $totalAmount,
                'paid_amount' => 0,
                'remaining_amount' => $totalAmount,
                 'remarks' => 'Accessory Charge',
                'status' => 'Pending',
            ]);

            //create Invoice Items
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'item_type' => 'accessory',
                'item_id' => $accessory->id,
                'description' => $accessory->accessoryHead->name,
                'price' => $accessory->price,
                'from_date' => $fromDate,
                'to_date' => $toDate,
                'month' => $months,
                'total_amount' => $totalAmount,
            ]);  

            // Attach accessory to the resident 
            // $studentAccessory = StudentAccessory::create([
            //     'resident_id' => $resident->id,
            //     'accessory_head_id' => $accessory->accessory_head_id,
            //     'price' => $accessory->price,
            //     'total_amount' => $totalAmount, // Store total amount
            //     'from_date' => $fromDate,
            //     'to_date' => $toDate,
            //     'due_date' => $dueDate
            // ]);

            // Automatically create a pending payment record
            // Payment::create([
            //     'resident_id' => $resident->id,
            //     'student_accessory_id' => $studentAccessory->id,
            //     'total_amount' => $totalAmount, // Store total amount
            //     'amount' => 0, // No initial payment
            //     'remaining_amount' => $totalAmount, // Ensure full amount is pending
            //     'payment_status' => 'Pending',
            //     'payment_method' => 'Null',
            //     'due_date' => $dueDate,
            // ]);

            DB::commit();

            return response()->json([
                'message' => 'Accessory added successfully, waiting for payment.',
                'total_amount' => $totalAmount,
                'remaining_amount' => $totalAmount,
                'from_date' => $fromDate,
                'to_date' => $toDate,
                'due_date' => $dueDate
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Failed to add accessory.',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function payAccessory(Request $request, $invoice_id)
    {
        $resident_id = Helper::get_resident_details($request->header('auth-id'))->id;
        // Validate payment request
        $validated = $request->validate([
            'transaction_id' => 'nullable|unique:payments,transaction_id',
            'payment_method' => 'required|in:Cash,UPI,Bank Transfer,Card,Other',
            'amount' => 'required|numeric|min:1',
        ]);

        DB::beginTransaction();
        try {
            $resident = Resident::findOrFail($resident_id);

            // Find the accessory record using `invoice_id`
            $invoice = Invoice::where('resident_id', $resident_id)
                ->find($invoice_id);
            // Ensure total amount is derived correctly
            $totalAmount = $invoice->total_amount;

            // Get total payments made so far for this accessory
            // $totalPaid = Payment::where('invoice_id', $invoice->id)->sum('amount');

            // Calculate remaining balance
            $remainingBalance = $invoice->remaining_amount;

            // Ensure payment does not exceed the remaining balance
            if ($validated['amount'] > $remainingBalance) {
                return response()->json([
                    'error' => 'Amount exceeds the remaining balance.',
                    'total_amount' => $totalAmount,
                    'remaining_balance' => $remainingBalance
                ], 400);
            }

            // Calculate new remaining amount after payment
            $newRemainingAmount = max($remainingBalance - $validated['amount'], 0);

            // Determine new payment status
            $paymentStatus = ($newRemainingAmount == 0) ? 'paid' : 'Partial';

            // Record the payment
            Invoice::where('id', $invoice->id)->update([
                'paid_amount' => $invoice->paid_amount + $validated['amount'],
                'remaining_amount' => $newRemainingAmount,
                'remarks' => 'Accessory Payment',
                'status' => $paymentStatus,
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Accessory payment recorded successfully.',
                'transaction_id' => $validated['transaction_id'],
                'remaining_balance' => $newRemainingAmount
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to process payment.',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    //getResidentInvoices
    public function getResidentInvoices(Request $request, $invoice_id)
    {
        $resident_id = Helper::get_resident_details($request->header('auth-id'))->id;
        try {
            $invoices = Invoice::find($invoice_id);
            return response()->json([
                'status' => 'success',
                'data' => $invoices
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'error' => 'Failed to fetch invoices.',
                'details' => $e->getMessage()
            ], 500);
        }
    }   

    public function adminSendAccessoryToResident(Request $request)
    {
        try {
        $request->validate([
            'resident_id' => 'required|exists:residents,id',
            'accessory_head_id' => 'required|exists:accessory_heads,id', // ✅ validate from accessory_heads table
            'duration' => 'required|in:1 Month,3 Months,6 Months,1 Year',
            'remarks' => 'nullable|string',
        ]);

        DB::beginTransaction();
            $resident = Resident::findOrFail($request->resident_id);

            // ✅ Find active accessory using accessory_head_id
            $accessory = Accessory::where('accessory_head_id', $request->accessory_head_id)
                ->with('accessoryHead')
                ->where('is_active', true)
                ->first();

            if (!$accessory) {
                return response()->json([
                    'error' => 'Active accessory not found for the given accessory_head_id'
                ], 404);
            }

            $fromDate = now();

            $months = match ($request->duration) {
                '1 Month' => 1,
                '3 Months' => 3,
                '6 Months' => 6,
                '1 Year' => 12,
            };

            $toDate = $fromDate->copy()->addMonths($months);
            $dueDate = now()->addDays($months * 30);
            $price = $accessory->price;
            $totalAmount = $price * $months;

            $nextId = (Invoice::max('id') ?? 0) + 1;
            $invoiceNumber = 'INV-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
            //create Invoice
            $invoice = Invoice::create([
                'resident_id' => $resident->id,
                'invoice_number' => $invoiceNumber,     
                'invoice_date' => now(),
                'due_date' => $dueDate,
                'total_amount' => $totalAmount, 
                'paid_amount' => 0,
                'remaining_amount' => $totalAmount,
                'remarks' => $request->remarks ?? 'Accessory Charge',
                'status' => 'Pending',
            ]); 
            //create Invoice Items
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'item_type' => 'accessory',
                'item_id' => $accessory->id,
                'description' => $accessory->accessoryHead->name,
                'price' => $accessory->price,
                'from_date' => $fromDate,   
                'to_date' => $toDate,
                'month' => $months,
                'total_amount' => $totalAmount,
            ]);
            
            DB::commit();

            return response()->json([
                'message' => 'Accessory assigned and payment created successfully.',
                'invoice_id' => $invoice->id,
                'payment_status' => 'Pending',
                'total_amount' => $totalAmount,
            ], 201);
        } catch (\Exception $e) {            
            DB::rollBack();
            Log::info($e->getMessage());
            return response()->json([
                'error' => 'Failed to assign accessory or create payment.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }



    public function getAccessories($resident_id)
    {
        try {
            $resident = Resident::with('accessories')->findOrFail($resident_id);

            $total_price = $resident->accessories->sum('pivot.price');

            return response()->json([
                'resident' => $resident,
                'accessories' => $resident->accessories,
                'total_price' => $total_price
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Resident not found.',
                'details' => $e->getMessage()
            ], 404);
        }
    }
}

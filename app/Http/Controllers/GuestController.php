<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Guest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\GuestAccessory;
use App\Models\AccessoryHead;
use App\Models\Accessory;
use App\Models\Fee;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Helper;
use App\Models\Invoice;
use App\Models\InvoiceItem;

class GuestController extends Controller
{
    public function register(Request $request)
    {
        Log::info('Guest registration request received', ['request' => $request->all()]);
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:guests,email',
                'faculty_id'    => 'required|exists:faculties,id',
                'department_id' => 'required|exists:departments,id',
                'course_id'     => 'required|exists:courses,id',
                'gender' => 'required|in:Male,Female,Other',
                'scholar_no' => 'required|unique:guests,scholar_no',
                'fathers_name' => 'required|string|max:255',
                'mothers_name' => 'required|string|max:255',
                'local_guardian_name' => 'nullable|string|max:255',
                'emergency_no' => 'required|string|max:20',
                'number' => 'nullable|string|max:20', 
                'parent_no' => 'nullable|string|max:20', 
                'guardian_no' => 'nullable|string|max:20', 
                'room_preference' => 'required|string|max:255', 
                'months' => 'nullable|integer|min:1|max:12',
                'accessories' => 'nullable|array',
                'accessories.*' => 'required|exists:accessory,id',
                'fee_waiver' => 'nullable|in:0,1',
                'bihar_credit_card' => 'nullable|in:0,1',
                'tnsd' => 'nullable|in:0,1',
                'remarks' => [
                    'nullable',
                    'string',
                    'max:1000',
                    'required_if:fee_waiver,1', // Required only if fee_waiver is 1
                ],
                'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120', // Optional file attachment (Max 5MB)
            ]);

            // Start a database transaction
            DB::beginTransaction();

            $months = $validatedData['months'] ?? 3;

            $attachmentPath = null;
            // Handle attachment upload if a file is present
            if ($request->hasFile('attachment')) {
                $attachmentPath = $request->file('attachment')->store('attachments', 'public');
            }

            // Prepare guest data for creation
            $guestData = collect($validatedData)->except(['accessory_head_ids', 'attachment'])->toArray();
            $guestData['months'] = $months;
            $guestData['attachment_path'] = $attachmentPath; // Add the attachment path

            // Ensure fee_waiver is a binary value
            $guestData['fee_waiver'] = $validatedData['fee_waiver'] ? 1 : 0;
            $guestData['bihar_credit_card'] = $validatedData['bihar_credit_card'] ? 1 : 0;
            $guestData['tnsd'] = $validatedData['tnsd'] ? 1 : 0;

            // Generate token and token expiry
            $token = Helper::generate_token();
            $guestData['token'] = $token;
            $guestData['token_expiry'] = Helper::generate_token_expiry();
            $guestData['status'] = 'pending';
            // Create the Guest record
            $guest = Guest::create($guestData);
            // Log::info("Guest created with ID: " , $guest);

            //fetch active fees
            $fees = Fee::where('is_active', 1)
            // ->whereDate('from_date', '<=', now())
            // ->whereDate('to_date', '>=', now())
            ->whereHas('feeHead', function ($q) {
                    $q->where('is_mandatory', 1);
                })
            ->get();
            Log::info("Active fees fetched: ", $fees->toArray());

            // Accessories selected from form
            $selectedAccessories = $request->accessories ?? []; // array of [id => price]
            $nextId = (Invoice::max('id') ?? 0) + 1;
            $invoiceNumber = 'INV-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);

            $invoice = Invoice::create([
                'guest_id'         => $guest->id,
                'invoice_number'   => $invoiceNumber,
                'invoice_date'     => now(),
                'due_date'         => now()->addDays($months * 30), // Due in 30 days
                'total_amount'     => 0,
                'paid_amount'      => 0,
                'remaining_amount' => 0,
                'status'           => 'pending', // created but not yet approved
            ]);

            $grandTotal = 0;
            // Add Fee Items
            foreach ($fees as $fee) {
                // Check if this fee is one-time (like Caution Money)
                if ($fee->feeHead && $fee->feeHead->is_one_time) {
                    $alreadyCharged = InvoiceItem::whereHas('invoice', function ($q) use ($guest) {
                        $q->where('guest_id', $guest->id);
                    })->where('description', $fee->name)->exists();

                    if ($alreadyCharged) {
                        continue; // Skip adding this fee again
                    }
                    // Charge once only
                    $amount = $fee->amount;
                    $monthsApplied = 1;
                }
                else {
                    // Normal fees → multiply by months
                    $amount = $fee->amount * $months;
                    $monthsApplied = $months;
                }
                $invoice->items()->create([
                    'item_type'    => 'fee',
                    'item_id'      => $fee->id,
                    'description'  => $fee->name,
                    'price'        => $fee->amount,
                    'from_date'    => now(),
                    'to_date'      => now()->addDays($monthsApplied * 30),
                    'total_amount' => $amount,
                ]);
                 $grandTotal += $amount;
            }

            // Add Accessory Items
            foreach ($selectedAccessories as $accId) {
                $accessory = Accessory::with('accessoryHead')->find($accId);
                $price     = $accessory?->price ?? 0;
                $amount    = $price * $months;
                $invoice->items()->create([
                    'item_type'    => 'accessory',
                    'item_id'      => $accessory?->id,
                    'description'  => $accessory?->accessoryHead?->name,
                    'price'        => $accessory?->price ?? 0,
                    'total_amount' => $amount,
                    'from_date'    => now(),
                    'to_date'      => now()->addDays($months * 30),
                ]);
                $grandTotal += $amount;
            }

            // Update invoice with totals
            $invoice->update([
                'total_amount'     => $grandTotal,
                'remaining_amount' => $grandTotal,
            ]);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Guest registered successfully.',
                'data' => $guest,
                'token' => $token,
                'errors' => null
            ], 201);
        } catch (ValidationException $e) {
            // Rollback the transaction on validation failure
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'data' => null,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) { // Catching a general Exception for broader error handling
            // Rollback the transaction on any other unexpected error
            DB::rollBack();
            Log::error('Guest registration failed: ' . $e->getMessage(), ['exception' => $e]); // Log the full exception

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong during guest registration.',
                'data' => null,
                'errors' => ['exception' => $e->getMessage()]
            ], 500);
        }
    }

    public function getGuestProfile(Request $request)
    {
        try {
            $guest = Guest::Where('id', $request->header('auth-id'))
                ->first();

            if (!$guest) {
                return response()->json([
                    'success' => false,
                    'message' => 'Guest not found',
                    'data' => null,
                    'errors' => null,
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Guest profile fetched successfully',
                'data' => $guest,
                'errors' => null,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch Guest profile',
                'data' => null,
                'errors' => ['exception' => $e->getMessage()],
            ], 500);
        }
    }


    public function getGuestTotalAmount(Request $request)
    {
        try {
            $user = Helper::get_auth_guest_user($request);
            $guest = Guest::select('id', 'months', 'days', 'status', 'fee_waiver')->findOrFail($user->id);

            $months = $guest->months ?? 1;
            $days = $guest->days ?? 0;

            $guestAccessories = GuestAccessory::where('guest_id', $guest->id)->get();
            $accessoryTotal = $guestAccessories->sum('total_amount');
            $accessoryHeadIds = $guestAccessories->pluck('accessory_head_id');

            $hostelFee = 0;
            $messFee = 0;
            $cautionMoney = 0;
            $waiverFeeUpdated = false;

            if ($guest->status === 'waiver_approved') {
                $feeException = \App\Models\FeeException::where('guest_id', $guest->id)->first();

                if ($feeException) {
                    $hostelFee = $feeException->hostel_fee ?? 0;
                    $cautionMoney = $feeException->caution_money ?? 0;
                    $waiverFeeUpdated = true;
                }
            }


            if (!$waiverFeeUpdated) {
                $hostelFeePerMonth = Fee::whereHas('feeHead', fn($q) => $q->where('name', 'Hostel Fee'))
                    ->where('is_active', true)
                    ->latest('from_date')
                    ->value('amount') ?? 0;

                $messFeePerMonth = Fee::whereHas('feeHead', fn($q) => $q->where('name', 'Mess Fee'))
                    ->where('is_active', true)
                    ->latest('from_date')
                    ->value('amount') ?? 0;

                $cautionMoney = Fee::whereHas('feeHead', fn($q) => $q->where('name', 'Caution Money'))
                    ->where('is_active', true)
                    ->latest('from_date')
                    ->value('amount') ?? 0;

                $hostelFee = $hostelFeePerMonth * $months;
                $messFee = $messFeePerMonth * $months;
            }

            $finalTotal = $accessoryTotal + $hostelFee + $messFee + $cautionMoney;

            return response()->json([
                'success' => true,
                'message' => 'Guest total amount fetched successfully.',
                'data' => [
                    'guest_id' => $guest->id,
                    'months' => $months,
                    'days' => $days,
                    'total_accessory_amount' => $accessoryTotal,
                    'hostel_fee' => $hostelFee + $messFee,
                    'caution_money' => $cautionMoney,
                    'final_total_amount' => $finalTotal,
                    'accessory_head_ids' => $accessoryHeadIds,
                    'waiver_fee_updated' => $waiverFeeUpdated,
                ],
                'errors' => null
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Guest not found',
                'data' => null,
                'errors' => null
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch data',
                'data' => null,
                'errors' => ['exception' => $e->getMessage()]
            ], 500);
        }
    }




    public function pendingGuests(Request $request)
    {
        try {
            $user = Helper::get_auth_guest_user($request);
            $guests = Guest::Where('id',$user->id)
            // ->Where('is_verified',1)
            ->with(['accessories.accessoryHead:id,name'])
            ->whereNotIn('status', ['paid', 'approved', 'rejected', 'waiver_approved','waiver_rejected'])
            ->with('feeException')
            ->get();
            
            return response()->json([
                'success' => true,
                'message' => 'Pending guests with accessories fetched successfully',
                'data' => $guests,
                'errors' => null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch pending guests',
                'data' => null,
                'errors' => ['exception' => $e->getMessage()]
            ], 500);
        }
    }


    public function pendingGuestsForAccountant(Request $request)
    {
        try {
            $user = \App\Models\User::find(request()->header('auth-id'));
            // Only fetch guests whose status is NOT 'paid' or 'rejected'
            $guests = Guest::whereHas('faculty', function ($q) use ($user) {
                $q->where('university_id', $user->university_id);
            }) 
            ->with([
                'accessories.accessory'
            ])
            ->where('status', 'pending')
            ->where('is_verified', 1)
            ->get();

            return response()->json([
                'success' => true,
                'message' => 'Pending guests with accessories fetched successfully',
                'data' => $guests,
                'errors' => null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch pending guests',
                'data' => null,
                'errors' => ['exception' => $e->getMessage()]
            ], 500);
        }
    }


    public function guestsStatus()
    {
        try {
            $user = \App\Models\User::find(request()->header('auth-id'));
            // Only fetch guests whose status is NOT 'paid' or 'rejected'
            $guests = Guest::with([
                'accessories'
            ])
            ->whereHas('faculty', function($q) use ($user) {
                $q->where('university_id', $user->university_id);
            })
            ->get();

            return response()->json([
                'success' => true,
                'message' => 'Pending guests with accessories fetched successfully',
                'data' => $guests,
                'errors' => null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch pending guests',
                'data' => null,
                'errors' => ['exception' => $e->getMessage()]
            ], 500);
        }
    }


    public function getPaidGuests(Request $request) 
    {
        Log::info('Fetching paid guests');
        try {
            $guests = Guest::where('status', 'paid')->get();
            Log::info("Paid Guests fetched: " . json_encode($guests));

            if ($guests->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'This guest is not found with paid status.',
                    'data' => [],
                    'errors' => null
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Paid guests fetched successfully.',
                'data' => $guests,
                'errors' => null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server Error',
                'data' => null,
                'errors' => ['exception' => $e->getMessage()]
            ], 500);
        }
    }


    public function getApprovedOrRejectedGuests(Request $request)
    {
        try {
            $user = Helper::get_auth_guest_user($request);
            $guests = Guest::where('id',$user->id)->whereIn('status', ['approved', 'rejected', 'pending', 'waiver_approved', 'waiver_rejected'])->get();

            return response()->json([
                'success' => true,
                'message' => 'Approved, rejected, or pending guests retrieved successfully',
                'data' => $guests,
                'errors' => null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch guests',
                'data' => null,
                'errors' => ['exception' => $e->getMessage()]
            ], 500);
        }
    }


    public function showPendingGuests()
    {
        $guests = Guest::with(['accessories.accessoryHead:id,name'])
            ->whereNotIn('status', ['paid', 'approved', 'rejected'])
            ->get();

        return view('admin.Pending_guest', compact('guests'));
    }

    // In your GuestController or a relevant controller
    public function getTotalAmount(Guest $guest) // Assuming route model binding
    {
        try {
            // You'll need to fetch the relevant fee/payment details.
            // This might come from the Guest model directly, or a related model like FeeException or a Payments model.
            // Let's assume for this example, some fields are on the Guest model and some on FeeException.
            // Ensure FeeException is eagerly loaded if you need its data.
            $guest->load('feeException'); // Load feeException if it's related

            $data = [
                'hostel_fee' => $guest->hostel_fee ?? 0, // Assuming hostel_fee is on Guest or fetched
                'caution_money' => $guest->caution_money ?? 0, // Assuming caution_money is on Guest or fetched
                'months' => $guest->feeException->months ?? null, // Example: Months from FeeException
                'days' => $guest->feeException->days ?? null,     // Example: Days from FeeException
                'facility' => $guest->feeException->facility ?? null, // Example: Facility from FeeException
                'remarks' => $guest->remarks ?? null, // Guest's general remarks
                'approved_by' => $guest->feeException->approved_by ?? null, // Example: Approved by from FeeException
                'document_path' => $guest->feeException->document_path ?? null, // Example: Document path from FeeException
            ];

            return response()->json([
                'success' => true,
                'message' => 'Payment details fetched successfully.',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            Log::error("Error fetching total amount for guest {$guest->id}: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch payment details.',
                'errors' => ['server_error' => $e->getMessage()]
            ], 500);
        }
    }

    public function guestDetails($guest_id)
    {
        try {
            $guest = Guest::with('accessories')->find($guest_id);
            return response()->json([
                'success' => true,
                'message' => 'Guest details fetched successfully',
                'data' => $guest,
                'errors' => null
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Guest not found',
                'data' => null,
                'errors' => null
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch guest details',
                'data' => null,
                'errors' => ['exception' => $e->getMessage()]
            ], 500);
        }
    }








    public function initiateGuestPayment(Request $request, PaytmPaymentService $paytm)
    {
        $validated = $request->validate([
            'guest_id' => 'required|integer',
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|string',
            'remarks' => 'nullable|string',
            'transaction_id' => 'nullable',
            'accessory_head_ids' => 'array'
        ]);

        $guest_id =  $validated['guest_id'];
        // $orderId = $this->generateOrderId(); // e.g., ORD-GUEST-20250828-XXXX
        // $origin = $request->header('origin-url') ?? $request->fullUrl();
        $origin = $request->header('origin-url') ?? $request->fullUrl();

        $order = Order::create([
            // 'reference_id' => $orderId,
            // 'order_id' => $orderId,
            'user_id' => null,
            'guest_id' => $guest_id,
            'purpose' => 'guest_payment',
            'origin_url' => $origin,
            'redirect_url' => route('paytm.callback'),
            'metadata' => json_encode([
                'guest_id' => $validated['guest_id'],
                'accessory_head_ids' => $validated['accessory_head_ids'] ?? [],
                'remarks' => $validated['remarks'] ?? null
            ]),
            'amount' => $validated['amount'],
            'transaction_id' => $validated['transaction_id'] ?? null
        ]);

        $orderId = $order->order_id ?? rand(1000, 50000);

        // Generate Paytm parameters
        $paytmParams = $paytm->initiateTransaction($orderId, $validated['guest_id'], $validated['amount']);

        $paytmParams["RETURN_URL"]   = "payment/status?order_id=" . $orderId; // user redirect

        // Return JSON for frontend to auto-submit form
        return response()->json([
            'success' => true,
            'message' => 'Payment initiation successful.',
            'paytm_params' => [
                'txnUrl' => $paytmParams['txnUrl'], // e.g., https://securegw.paytm.in/the-url
                'body' => $paytmParams['body']       // contains MID, ORDER_ID, TXN_AMOUNT, CHECKSUMHASH, etc.
            ]
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\Fee;
use App\Models\Guest;
use App\Models\Order;
use App\Helpers\Helper;
use App\Models\Accessory;
use Illuminate\Http\Request;
use App\Models\GuestAccessory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Services\PaytmPaymentService;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GuestController extends Controller
{
    // public function register(Request $request)
    // {
    //     $request->merge([
    //         'accessories' => is_string($request->accessories)
    //             ? json_decode($request->accessories, true)
    //             : $request->accessories
    //     ]);

    //     Log::alert($request->all());
    //     try {

    //         $validatedData = $request->validate([
    //             'name' => 'required|string|max:255',
    //             'email' => 'required|email|unique:guests,email',
    //             'faculty_id'    => 'required|exists:faculties,id',
    //             'department_id' => 'required|exists:departments,id',
    //             // 'course_id'     => 'required|exists:courses,id',
    //             'course_id'     => 'required|exists:courses,id',
    //             'gender' => 'required|in:Male,Female,Other',
    //             // 'scholar_number' => 'required|unique:guests,scholar_number',
    //             'scholar_number' => 'required|unique:guests,scholar_number',
    //             'fathers_name' => 'required|string|max:255',
    //             'mothers_name' => 'required|string|max:255',
    //             'local_guardian_name' => 'nullable|string|max:255',
    //             //'emergency_contact' => 'required|string|max:20',
    //             'emergency_contact' => 'required|string|max:20',
    //             // 'number' => 'nullable|string|max:20',
    //             'mobile' => 'nullable|string|max:20',
    //             //'parent_no' => 'nullable|string|max:20',
    //             'parent_contact' => 'nullable|string|max:20',
    //             //'guardian_no' => 'nullable|string|max:20',
    //             'guardian_contact' => 'nullable|string|max:20',
    //             'room_preference' => 'nullable|string|max:255',
    //             'months' => 'nullable|integer|min:1|max:12',
    //             // 'accessory_head_ids' => 'nullable|array',
    //             // 'accessory_head_ids.*' => 'exists:accessory_heads,id',
    //             'accessories' => 'nullable|array',
    //             'accessories.*' => 'exists:accessories_heads,id',
    //             'fee_waiver' => 'nullable|in:0,1',
    //             'bihar_credit_card' => 'nullable|in:0,1',
    //             'tnsd' => 'nullable|in:0,1',
    //             'remarks' => [
    //                 'nullable',
    //                 'string',
    //                 'max:1000',
    //                 'required_if:fee_waiver,1', // Required only if fee_waiver is 1
    //             ],
    //             'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120', // Optional file attachment (Max 5MB)
    //             'agree' => 'nullable',
    //         ]);

    //         Log::info('here');



    //         // 🔄 Rename attributes to match commented ones
    //         // $validatedData['course_id'] = \App\Course::where('name', $validatedData['course'])->value('id');
    //         // unset($validatedData['course']);

    //         // $validatedData['scholar_number'] = $validatedData['scholar_number'];
    //         // unset($validatedData['scholar_number']);

    //         // $validatedData['emergency_contact'] = $validatedData['emergency_contact'];
    //         // unset($validatedData['emergency_contact']);

    //         // $validatedData['number'] = $validatedData['mobile'] ?? null;
    //         // unset($validatedData['mobile']);

    //         // $validatedData['parent_no'] = $validatedData['parent_contact'] ?? null;
    //         // unset($validatedData['parent_contact']);

    //         // $validatedData['guardian_no'] = $validatedData['guardian_contact'] ?? null;
    //         // unset($validatedData['guardian_contact']);

    //         $validatedData['accessory_head_ids'] = $validatedData['accessories'];

    //         unset($validatedData['accessories']);

    //         Log::alert('here');
    //         // Start a database transaction
    //         DB::beginTransaction();

    //         $months = $validatedData['months'] ?? 3;

    //         $attachmentPath = null;
    //         // Handle attachment upload if a file is present
    //         if ($request->hasFile('attachment')) {
    //             $attachmentPath = $request->file('attachment')->store('attachments', 'public');
    //         }

    //         // Prepare guest data for creation
    //         $guestData = collect($validatedData)->except(['accessory_head_ids', 'attachment'])->toArray();
    //         $guestData['months'] = $months;
    //         $guestData['attachment_path'] = $attachmentPath; // Add the attachment path

    //         // Ensure fee_waiver is a binary value
    //         // $guestData['fee_waiver'] = isset($validatedData['fee_waiver']) ? 1 : 0;
    //         // $guestData['bihar_credit_card'] = isset($validatedData['bihar_credit_card']) ? 1 : 0;   
    //         // $guestData['tnsd'] = isset($validatedData['tnsd']) ? 1 : 0;
    //         $guestData['fee_waiver'] = $validatedData['fee_waiver'] ?? 0;
    //         $guestData['bihar_credit_card'] = $validatedData['bihar_credit_card'] ?? 0;
    //         $guestData['tnsd'] = $validatedData['tnsd'] ?? 0;


    //         // Generate token and token expiry
    //         $token = Helper::generate_token();
    //         $guestData['token'] = $token;
    //         $guestData['token_expiry'] = Helper::generate_token_expiry();

    //         // Create the Guest record
    //         $guest = Guest::create($guestData);

    //         // Log::info("Guest created with ID: " , $guest);

    //         // Handle accessories if provided
    //         if (!empty($validatedData['accessory_head_ids'])) {
    //             $fromDate = Carbon::now();
    //             $toDate = Carbon::now()->addMonths($months);

    //             foreach ($validatedData['accessory_head_ids'] as $headId) {
    //                 $accessory = Accessory::where('accessory_head_id', $headId)
    //                     ->where('is_active', true)
    //                     ->latest('from_date')
    //                     ->first();

    //                 if ($accessory) {
    //                     GuestAccessory::create([
    //                         'guest_id' => $guest->id,
    //                         'accessory_head_id' => $headId,
    //                         'price' => $accessory->price,
    //                         'total_amount' => $accessory->price * $months,
    //                         'from_date' => $fromDate,
    //                         'to_date' => $toDate
    //                     ]);
    //                 }
    //             }
    //         }

    //         // Commit the transaction if everything was successful
    //         DB::commit();

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Guest registered successfully.',
    //             'data' => $guest,
    //             'token' => $token,
    //             'errors' => null
    //         ], 201);
    //     } catch (ValidationException $e) {
    //         // Rollback the transaction on validation failure
    //         DB::rollBack();
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Validation failed',
    //             'data' => null,
    //             'errors' => $e->errors()
    //         ], 422);
    //     } catch (\Exception $e) { // Catching a general Exception for broader error handling
    //         // Rollback the transaction on any other unexpected error
    //         DB::rollBack();
    //         Log::error('Guest registration failed: ' . $e->getMessage(), ['exception' => $e]); // Log the full exception

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Something went wrong during guest registration.',
    //             'data' => null,
    //             'errors' => ['exception' => $e->getMessage()]
    //         ], 500);
    //     }
    // }

    public function register(Request $request)
    {
        // Normalize accessories input
        $request->merge([
            'accessories' => is_string($request->accessories)
                ? json_decode($request->accessories, true)
                : $request->accessories
        ]);

        Log::alert($request->all());

        try {
            // Validate incoming request
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:guests,email',
                'faculty_id' => 'required|exists:faculties,id',
                'department_id' => 'required|exists:departments,id',
                'course_id' => 'required|exists:courses,id',
                'gender' => 'required|in:Male,Female,Other',
                'scholar_number' => 'required|unique:guests,scholar_number',
                'fathers_name' => 'required|string|max:255',
                'mothers_name' => 'required|string|max:255',
                'local_guardian_name' => 'nullable|string|max:255',
                'emergency_contact' => 'required|string|max:20',
                'mobile' => 'nullable|string|max:20',
                'parent_contact' => 'nullable|string|max:20',
                'guardian_contact' => 'nullable|string|max:20',
                'room_preference' => 'nullable|string|max:255',
                'months' => 'nullable|integer|min:1|max:12',
                'accessories' => 'nullable|array',
                'accessories.*' => 'exists:accessory_heads,id',
                'fee_waiver' => 'nullable|in:0,1',
                'bihar_credit_card' => 'nullable|in:0,1',
                'tnsd' => 'nullable|in:0,1',
                'remarks' => [
                    'nullable',
                    'string',
                    'max:1000',
                    'required_if:fee_waiver,1',
                ],
                'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'agree' => 'nullable',
            ]);

            DB::beginTransaction();

            // Handle file upload
            $attachmentPath = $request->hasFile('attachment')
                ? $request->file('attachment')->store('attachments', 'public')
                : null;

            // Prepare data for Guest model
            $guestData = [
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'faculty_id' => $validatedData['faculty_id'],
                'department_id' => $validatedData['department_id'],
                'course_id' => $validatedData['course_id'],
                'gender' => $validatedData['gender'],
                'scholar_number' => $validatedData['scholar_number'],
                'fathers_name' => $validatedData['fathers_name'],
                'mothers_name' => $validatedData['mothers_name'],
                'local_guardian_name' => $validatedData['local_guardian_name'] ?? null,
                'emergency_contact' => $validatedData['emergency_contact'], // ✅ This line is essential
                'mobile' => $validatedData['mobile'] ?? null,
                'parent_contact' => $validatedData['parent_contact'] ?? null,
                'guardian_contact' => $validatedData['guardian_contact'] ?? null,
                'room_preference' => $validatedData['room_preference'] ?? null,
                'months' => $validatedData['months'] ?? 3,
                'fee_waiver' => $validatedData['fee_waiver'] ?? 0,
                'bihar_credit_card' => $validatedData['bihar_credit_card'] ?? 0,
                'tnsd' => $validatedData['tnsd'] ?? 0,
                'remarks' => $validatedData['remarks'] ?? null,
                'attachment_path' => $attachmentPath,
                'token' => Helper::generate_token(),
                'token_expiry' => Helper::generate_token_expiry(),
            ];


            // Create Guest record
            $guest = Guest::create($guestData);

            // Handle accessories
            if (!empty($validatedData['accessories'])) {
                $fromDate = Carbon::now();
                $toDate = Carbon::now()->addMonths($guestData['months']);

                foreach ($validatedData['accessories'] as $headId) {
                    $accessory = Accessory::where('accessories_heads_id', $headId)
                        ->where('is_active', true)
                        ->latest('from_date')
                        ->first();

                    if ($accessory) {
                        GuestAccessory::create([
                            'guest_id' => $guest->id,
                            'accessories_heads_id' => $headId,
                            'price' => $accessory->price,
                            'total_amount' => $accessory->price * $guestData['months'],
                            'from_date' => $fromDate,
                            'to_date' => $toDate,
                        ]);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Guest registered successfully.',
                'data' => $guest,
                'token' => $guestData['token'],
                'errors' => null,
            ], 201);
        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'data' => null,
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Guest registration failed: ' . $e->getMessage(), ['exception' => $e]);

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong during guest registration.',
                'data' => null,
                'errors' => ['exception' => $e->getMessage()],
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
            $accessoryHeadIds = $guestAccessories->pluck('accessories_heads_id');

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
            $guests = Guest::Where('id', $user->id)
                // ->Where('is_verified',1)
                ->with(['accessories.accessoryHead:id,name'])
                ->whereNotIn('status', ['paid', 'approved', 'rejected', 'waiver_approved', 'waiver_rejected'])
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
                    'accessories.accessoryHead:id,name'
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
                'accessories.accessoryHead:id,name'
            ])
                ->whereHas('faculty', function ($q) use ($user) {
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
        try {
            $user = Helper::get_auth_guest_user($request);
            $guests = Guest::find($user->id)->where('status', 'paid')->get();

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
            $guests = Guest::where('id', $user->id)->whereIn('status', ['approved', 'rejected', 'pending', 'waiver_approved', 'waiver_rejected'])->get();

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
            $guest = Guest::with(['accessories.accessoryHead:id,name'])
                ->where('id', $guest_id)
                ->firstOrFail();
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


    public function retry(Request $request)
    {
        $orderId = $request->query('order');

        if (!$orderId) {
            return response()->json([
                'status' => 'error',
                'message' => 'Missing order ID.'
            ], 400);
        }

        $order = Order::find($orderId);

        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found.'
            ], 404);
        }

        // Extract user data from the order
        $userData = json_decode($order->user_data, true);

        return response()->json([
            'success' => true,
            'message' => 'Order retrieved successfully.',
            'order_id' => $order->id,
            'amount' => $order->amount,
            'payment_method' => $order->payment_method ?? null,
            'remarks' => $userData['remarks'] ?? null,
            'guest_id' => $userData['guest_id'] ?? null,
            'accessory_head_ids' => $userData['accessory_head_ids'] ?? [],
            'retry_url' => url("/api/payments/initiate") // or wherever you re-initiate
        ]);
    }


    public static function generateOrderId()
    {
        $count = Order::count();
        $seq = $count + 1;
        $seqStr = strval($seq);

        // Pad to minimum 4 digits
        if (strlen($seqStr) < 4) {
            $seqStr = str_pad($seqStr, 4, '0', STR_PAD_LEFT);
        }

        // Replace each '0' with a random uppercase letter
        $seqStr = preg_replace_callback('/0/', function () {
            return chr(rand(65, 90)); // A–Z
        }, $seqStr);

        $prefix = 'G-ORD';
        $date = now()->format('ymd'); // e.g. 250826

        return "{$prefix}{$date}{$seqStr}";
    }
}

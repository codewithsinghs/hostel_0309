<?php

namespace App\Http\Controllers;

use Exception;
use Throwable;
use App\Models\Guest;
use App\Models\Order;
use App\Models\Accessory;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\PaymentHandler;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Services\PaytmPaymentService;

class PaytmController extends Controller
{

    // public function callback(Request $request, PaytmService $paytm)
    // {
    //     $result = $paytm->verifyCallback($request->all());

    //     if (!$result['valid']) {
    //         return redirect('/')->with('error', 'Checksum mismatch');
    //     }

    //     $orderId = $result['data']['ORDERID'];
    //     $referenceId = Str::after($orderId, 'ORD-');
    //     $payment = Payment::where('reference_id', $referenceId)->first();

    //     if (!$payment) {
    //         return redirect('/')->with('error', 'Payment reference not found');
    //     }

    //     // Redirect to the original controller route with payment data
    //     return redirect()->route($payment->callback_route, [
    //         'payment_id' => $payment->id,
    //         'status' => $result['data']['STATUS'],
    //         'txn_id' => $result['data']['TXNID'],
    //     ]);
    // }

    // public function callback(Request $request, PaytmPaymentService $paytm)
    // {
    //     Log::info('callback');
    //     $result = $paytm->verifyCallback($request->all());

    //     $orderId = $result['data']['ORDERID'] ?? null;
    //     $referenceId = Str::after($orderId, 'ORD-');
    //     $order = Order::where('reference_id', $referenceId)->first();

    //     if (!$order || !$result['valid']) {
    //         return redirect('/')->with('error', 'Invalid payment response');
    //     }

    //     Transaction::create([
    //         'order_id' => $orderId,
    //         'transaction_id' => $result['data']['TXNID'] ?? null,
    //         'status' => $result['data']['STATUS'] ?? null,
    //         'payment_mode' => $result['data']['PAYMENTMODE'] ?? null,
    //         'bank_name' => $result['data']['BANKNAME'] ?? null,
    //         'currency' => $result['data']['CURRENCY'] ?? null,
    //         'response_code' => $result['data']['RESPCODE'] ?? null,
    //         'response_message' => $result['data']['RESPMSG'] ?? null,
    //         'response_payload' => json_encode($result['data']),
    //     ]);

    //     $order->update(['status' => $result['data']['STATUS']]);

    //     return redirect($order->redirect_url)->with([
    //         'payment_status' => $result['data']['STATUS'],
    //         'order' => $order,
    //         'user_data' => json_decode($order->user_data, true),
    //     ]);
    // }


    // Well done
    // public function callback(Request $request, PaytmPaymentService $paytm)
    // {
    //     Log::info('Received Paytm callback', ['payload' => $request->all()]);

    //     try {
    //         $result = $paytm->verifyCallback($request);

    //         if (!$result['valid']) {
    //             Log::warning('Invalid Paytm signature', ['payload' => $result['data']]);
    //             $order = $result['ORDERID']; // or however you retrieve it
    //             Log::warning('sending error for order:' . $order);
    //             return $this->handlePaymentError('Invalid payment response. Please try again.', $order);
    //         }

    //         $orderId = $result['data']['ORDERID'] ?? null;

    //         // $referenceId = Str::after($orderId, 'ORD-');
    //         // $order = Order::where('reference_id', $referenceId)->first();
    //         $order = Order::where('order_id', $orderId)->first();
    //         Log::info('found order' . $order);
    //         if (!$order) {
    //             Log::error('Order not found', ['order_id' => $orderId]);
    //             return $this->handlePaymentError('Order not found. Please contact support.');
    //         }

    //         $status = $result['data']['STATUS'] ?? 'TXN_FAILURE';

    //         if ($status === 'TXN_FAILURE') {
    //             Log::info('Transaction failed', ['order_id' => $order->id]);
    //             $order = Order::find($order->id);
    //             return $this->handlePaymentError('Transaction failed. Please retry or check with your bank.', $order);
    //         }

    //         Transaction::create([
    //             'order_id' => $order->id,
    //             'transaction_id' => $result['data']['TXNID'] ?? null,
    //             'status' => $status,
    //             'payment_mode' => $result['data']['PAYMENTMODE'] ?? null,
    //             'bank_name' => $result['data']['BANKNAME'] ?? null,
    //             'currency' => $result['data']['CURRENCY'] ?? null,
    //             'response_code' => $result['data']['RESPCODE'] ?? null,
    //             'response_message' => $result['data']['RESPMSG'] ?? null,
    //             'response_payload' => json_encode($result['data']),
    //         ]);

    //         $order->update(['status' => $status]);

    //         Log::info('Transaction recorded successfully', ['order_id' => $order->id]);

    //         return redirect($order->redirect_url)->with([
    //             'payment_status' => $status,
    //             'order' => $order,
    //             'user_data' => json_decode($order->user_data, true),
    //         ]);
    //     } catch (\Throwable $e) {
    //         Log::critical('Unexpected error during Paytm callback', [
    //             'exception' => $e,
    //             'payload' => $request->all(),
    //         ]);
    //         return $this->handlePaymentError('Something went wrong. Please try again later.')->with('order_id', $order->id);
    //     }
    // }

    // private function handlePaymentError(string $message)
    // {
    //     return redirect('/')->with('error', $message)->with('tips', [
    //         'Check payment status with your bank to avoid double payment',
    //         'Clear cookies & temporary internet files of the browser & retry',
    //         'Launch a new browser & start from the beginning',
    //     ]);
    // }

    // private function handlePaymentError(string $message, ?Order $order = null)
    // {
    //     Log::info("recieved handle error");
    //     if ($order) {
    //         Log::info("recieved order ". $order);
    //         $order->update([
    //             'status' => 'PAYMENT_FAILED',
    //             'message' => $message,
    //         ]);

    //         Log::warning('Order marked as PAYMENT_FAILED', ['order_id' => $order->id]);

    //         $redirectUrl = $order->origin_url ?? route('payment.retry', ['order' => $order->id]);
    //     } else {
    //         Log::info("recieved order error". $order);
    //         $redirectUrl = session('origin_url', route('home'));
    //     }

    //     return redirect($redirectUrl)->with([
    //         'error' => $message,
    //         'tips' => [
    //             'Check payment status with your bank to avoid double payment',
    //             'Clear cookies & temporary internet files of the browser & retry',
    //             'Launch a new browser & start from the beginning',
    //         ],
    //     ]);
    // }

    // private function handlePaymentError(string $message, ?Order $order = null)
    // {
    //     Log::info("Received handle error");

    //     $tips = [
    //         'Check payment status with your bank to avoid double payment',
    //         'Clear cookies & temporary internet files of the browser & retry',
    //         'Launch a new browser & start from the beginning',
    //     ];

    //     if ($order) {
    //         Log::info("Received order: " . $order->id);

    //         $order->update([
    //             'status' => 'PAYMENT_FAILED',
    //             'message' => $message,
    //         ]);

    //         Log::warning('Order marked as PAYMENT_FAILED', ['order_id' => $order->id]);

    //         return response()->json([
    //             'status' => 'error',
    //             'order_id' => $order->id,
    //             'message' => $message,
    //             'tips' => $tips,
    //             'retry_url' => $order->origin_url ?? route('payment.retry', ['order' => $order->id]),
    //         ], 400);
    //     }

    //     Log::info("No order provided");

    //     return response()->json([
    //         'status' => 'error',
    //         'message' => $message,
    //         'tips' => $tips,
    //         'redirect_url' => session('origin_url', route('home')),
    //     ], 400);
    // }

    // private function handlePaymentSuccess(Order $order)
    // {
    //     Log::info('Payment successful for order', ['order_id' => $order->id]);

    //     $order->update([
    //         'status' => 'PAID',
    //         'message' => 'Payment completed successfully',
    //     ]);

    //     return response()->json([
    //         'status' => 'success',
    //         'order_id' => $order->id,
    //         'message' => 'Payment completed successfully',
    //         'redirect_url' => $order->origin_url,
    //     ]);
    // }


    // Universal handdler
    public function callback(Request $request, PaytmPaymentService $paytm)
    {
        Log::info('Received Paytm callback', ['payload' => $request->all()]);

        $orderId = null; // ✅ Declare early

        try {

            $result = $paytm->verifyCallback($request);

            Log::info('recived verify result', $result);
            if (!$result['valid']) {
                Log::warning('Invalid Paytm signature', ['payload' => $result['data']]);
                $orderId = $result['data']['ORDERID'] ?? null;

                return $this->respondWithError(
                    'Invalid payment response. Please try again.',
                    $orderId,
                    $request
                );
            }

            $orderId = $result['data']['ORDERID'] ?? null;
            $order = Order::where('order_id', $orderId)->first();

            if (!$order) {
                Log::error('Order not found', ['order_id' => $orderId]);
                return $this->respondWithError('Order not found. Please contact support.', $orderId, $request);
            }

            $status = $result['data']['STATUS'] ?? '';

            Log::info('payment_status' . $status);

            $order->update([
                'status' => $status,
                'message' => $result['data']['RESPMSG'] ?? null,
                'payment_method' => $result['data']['PAYMENTMODE'] ?? null,

            ]);

            Log::info('Order Updated successfully', $order->toArray());

            // if ($status === 'TXN_FAILURE') {
            //     Log::info('Transaction failed', ['order_id' => $order->id]);
            //     return $this->respondWithError('Transaction failed. Please retry or check with your bank.', $order->id, $request);
            // }

            if ($status === 'TXN_FAILURE') {
                Log::info('Transaction failed', [
                    'order_id' => $order->id,
                    'reason' => $result['data']['RESPMSG'] ?? 'Unknown failure'
                ]);

                return $this->respondWithError(
                    $result['data']['RESPMSG'] ?? 'Transaction failed. Please retry or check with your bank.',
                    $order->id,
                    $request
                );
            }

            $transaction = Transaction::create([
                'order_id' => $order->id,
                'txn_id' => $result['data']['TXNID'] ?? null,
                'status' => $status,
                'bank_txn_id' => $result['data']['BANKTXNID'] ?? null,
                'txn_amount' => $result['data']['TXNAMOUNT'] ?? null,
                'payment_mode' => $result['data']['PAYMENTMODE'] ?? null,
                'bank_name' => $result['data']['BANKNAME'] ?? null,
                'currency' => $result['data']['CURRENCY'] ?? null,
                'm_id' => $result['data']['MID'] ?? null,
                'response_code' => $result['data']['RESPCODE'] ?? null,
                'response_message' => $result['data']['RESPMSG'] ?? null,
                'response_payload' => json_encode($result['data']),
            ]);

            // $order->update([
            //     'status' => $status,
            //     'message' => $result['data']['RESPMSG'] ?? null,
            //     'payment_method' => $result['data']['PAYMENTMODE'] ?? null,

            // ]);

            Log::info('Transaction recorded successfully', $transaction->toArray());

            Log::info('order_id' . $orderId);
            // Log::info('redirect_url' . $$order->redirect_url);
            // Log::info('user_data', json_decode($order->user_data, true));

            $retryUrl = url("/api/guest/makepayment");

            $responseData = [
                'payment_status' => $status,
                'order_id' => $order->order_id,
                'transaction_id' => $transaction->txn_id,
                'amount' => $transaction->txn_amount,
                'payment_mode' => $transaction->payment_mode,
                'redirect_url' => $order->redirect_url ?? $retryUrl,
                'user_data' => json_decode($order->user_data, true),
            ];

            // Log::info('response after transaction' . $responseData);

            Log::info('Response data before redirect', ['responseData' => $responseData]);

            // ✅ Or replace with:
            Log::info('Redirecting after successful transaction');

            $message = 'Payment successful';

            // return redirect($recieptUrl)->with([
            //     'payment_status' => $status,
            //     'success'        => true,
            //     'transaction_id' => $responseData['transaction_id'] ?? null,
            //     'order_id'       => $responseData['order_id'] ?? null,
            //     'amount'         => $responseData['txn_amount'] ?? null,
            //     'method'         => $responseData['payment_method'] ?? null,
            // ]);


            // return response()->json([
            //     'redirect_url'   => $receiptUrl,
            //     'payment_status' => $status,
            //     'success'        => true,
            //     'transaction_id' => $responseData['transaction_id'] ?? null,
            //     'order_id'       => $responseData['order_id'] ?? null,
            //     'amount'         => $responseData['amount'] ?? null,
            //     'payment_mode'         => $responseData['payment_mode'] ?? null,
            // ]);




            if ($transaction->status === 'TXN_SUCCESS') {
                try {
                    Log::info("Processing successful payment for order_id: {$orderId}");

                    $handler = app(PaymentHandler::class);
                    $handler->handle($order);

                    // $handler = app(PaymentHandler::class);
                    // $finalResult = $handler->handle($order);
                } catch (Exception $ex) {
                    Log::error("PaymentHandler failed for order_id {$orderId}", [
                        'error' => $ex->getMessage(),
                        'trace' => $ex->getTraceAsString()
                    ]);
                }
            } else {
                Log::warning("Payment failed or pending for order_id: {$orderId}", [
                    'status' => $transaction->status
                ]);
            }

            Log::info('Final Result');

            // return redirect()->away("payment/success?order_id={$transaction->order_id}");

            return redirect()->to("payment/status?order_id=" . $orderId);


            //  $receiptUrl = '/guest/payment/receipt';

            // // Safer way → pass encoded data to frontend
            // return redirect()->to($receiptUrl . '?data=' . urlencode(json_encode([
            //     'payment_status' => $status,
            //     'success' => $status === 'TXN_SUCCESS',
            //     'transaction_id' => $responseData['TXNID'] ?? null,
            //     'payment_method' => 'UPI', // you can map from response
            //     'remarks' => $responseData['RESPMSG'] ?? '',
            //     'guest_id' => $responseData['ORDERID'] ?? null, // depends on mapping
            //     'raw' => $responseData, // keep full for debugging
            // ])));

            // For redirect, exclude nested arrays
            // return redirect($order->redirect_url)->with([
            //     'payment_status' => $status,
            //     'order_id' => $order->id
            // ]);






        } catch (Throwable $e) {
            Log::debug('Error occurred in respondWithError()', ['orderId' => $orderId]);

            Log::critical('Unexpected error during Paytm callback', [
                'exception' => $e->getMessage(),
                'order_id' => $orderId,
                'payload' => $request->all(),
            ]);


            return $this->respondWithError('Something went wrong. Please try again later.', null, $request);
        }
    }

    // protected function respondWithError(string $message, $orderId = null, Request $request)
    // {
    //     $payload = [
    //         'status' => 'error',
    //         'message' => $message,
    //         'order_id' => $orderId,
    //         // 'retry_url' => route('payment.retry', ['order' => $orderId]),
    //         'retry_url' => $order->origin_url ?? url("/retry-payment?order={$orderId}")

    //     ];

    //     if ($request->expectsJson()) {
    //         return response()->json($payload, 400);
    //     }

    //     return redirect(route('payment.retry', ['order' => $orderId]))
    //         ->withErrors(['payment_error' => $message])
    //         ->with($payload);
    // }

    protected function respondWithError(string $message, $orderId = null, Request $request)
    {
        // Attempt to retrieve the order if an ID is provided
        $order = $orderId ? Order::find($orderId) : null;

        Log::info('got the order', ['order' => $order ? $order->toArray() : null]);

        // $retryUrl = $order && $order->origin_url
        //     ? $order->origin_url
        //     // : url("/retry-payment?order={$orderId}");
        //     // : url("/guest/makepayment?order={$orderId}");
        //     : url("/guest/payment");

        $retryUrl = url("/guest/payment");

        $payload = [
            'status' => 'error',
            'message' => $message,
            'order_id' => $orderId,
            'retry_url' => $retryUrl
        ];

        if ($request->expectsJson()) {
            return response()->json($payload, 400);
        }

        return redirect($retryUrl)
            ->withErrors(['payment_error' => $message])
            ->with($payload);
    }

    //     fetch('/api/payment', {
    //     method: 'POST',
    //     body: JSON.stringify(paymentData),
    //     headers: { 'Content-Type': 'application/json' }
    // })
    // .then(res => res.json())
    // .then(data => {
    //     if (data.status === 'success') {
    //         window.location.href = data.redirect_url;
    //     } else {
    //         showError(data.message, data.tips);
    //     }
    // })
    // .catch(err => {
    //     console.error('Unexpected error:', err);
    // });

    public function PaymentStatus(Request $request)
    {
        $authId = $request->header('auth-id'); // Get auth-id from headers
        // Log::alert($authId);

        //  Authenticate guest using token guard
        try {
            // $guest = Guest::findOrFail($authId);
            $guest = Guest::with('accessories')->find($authId);
            Log::alert($guest);

            // Format the response for frontend
            $accessories = $guest->accessories->map(function ($item) {
                return [
                    'name'  => $item->name,
                    'qty'   => $item->qty,
                    'price' => $item->price,
                    'from_date' => $item->from_date,
                    'to_date' => $item->to_date,
                    'total_amount' => $item->total_amount,
                    'is_default' => $item->is_default,
                ];
            });

            Log::alert($accessories);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }


        $order = Order::with(['transaction', 'guest'])
            ->where('order_id', $request->order_id)
            // ->where('guest_id', $guest->id) // ensure guest owns the order
            ->first();

        // Log::info($order);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }

        // if ($order->guest_id != $authId) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Unauthorized'
        //     ], 401);
        // }

        // Log::info('amount fetched', $feeData);
        $response = [
            'success' => true,
            'status'  => $order->status,
            'txn_id'  => $order->transaction->txn_id ?? null,
            'amount'  => $order->transaction->txn_amount ?? null,
            'order_id' => $order->order_id,
            'guest'   => [
                'sc_n' => $order->guest->scholar_number ?? null,
                'name'  => $order->guest->name ?? null,
                'email' => $order->guest->email ?? null,
                'mobile' => $order->guest->mobile ?? null,
                'gender' => $order->guest->gender ?? null,
                'fathers_name' => $order->guest->fathers_name ?? null,
                'mothers_name' => $order->guest->mothers_name ?? null,
                'parent_contact' => $order->guest->parent_contact ?? null,
                'guardian_name' => $order->guest->local_guardian_name ?? null,
                'guardian_contact' => $order->guest->guardian_contact ?? null,
                'emergency_contact' => $order->guest->emergency_contact ?? null,
                'stay_duration' => $order->guest->month ?? null,
                'course' => $order->guest->course ?? null,

            ],
             'accessories' => $accessories,
        ];

        Log::info($response);

        return response()->json($response);
    }

    // public function PaymentStatus(Request $request)
    // {
    //     // $user = Auth::user(); // guest from token
    //     $authId = $request->header('auth-id'); // Get auth-id from headers
    //     // Log::alert($authId);

    //     //  $authId = 2;
    //     //  Authenticate guest using token guard
    //     // $guest = Auth::guard('guest')->user();

    //     // if (!$guest) {
    //     //     return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
    //     // }
    //     // Log::info('order Status', $user->toArray()); // correct

    //     // Log::info('order Status', (array) $user);
    //     // Log::info('order Status: ' . json_encode($user));
    //     // $order = Order::with(['guest', 'transaction'])
    //     //     ->where('order_id', $request->order_id)
    //     //     ->first();
    //     $order = Order::with(['transaction', 'guest'])
    //         ->where('order_id', $request->order_id)
    //         // ->where('guest_id', $guest->id) // ensure guest owns the order
    //         ->first();

    //     // Log::info($order);


    //     if (!$order) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Order not found'
    //         ], 404);
    //     }

    //     if ($order->guest_id != $authId) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Unauthorized'
    //         ], 401);
    //     }

    //     try {
    //         $controller = app(GuestController::class);
    //         Log::info('guestController');
    //         $apiResponse = $controller->getGuestTotalAmount($request);
    //         // $apiResponse = $controller->getGuestTotalAmount($request);
    //         Log::info('amount fetched' .  $apiResponse);

    //         // $apiResponse = $apiResponse instanceof \Illuminate\Http\JsonResponse
    //         //     ? $apiResponse->getData(true)   // true = associative array
    //         //     : json_decode($apiResponse, true);

    //         // Log::info('Amount fetch', $apiResponse);

    //         // Decode JSON into array
    //         // Decode JSON if it's a string
    //         // Convert JsonResponse -> PHP array
    //         $responseArray = $apiResponse->getData(true); // <-- true = return associative array

    //         // Now you can safely extract
    //         $data = $responseArray['data'];

    //         //  Log::info('Guest amount fetched', ['response' => $data]);

    //         $guestId = $data['guest_id'];
    //         $months  = $data['months'];
    //         $days    = $data['days'];
    //         $totalAccessoryAmount = $data['total_accessory_amount'];
    //         $hostelFee = $data['hostel_fee'];
    //         $cautionMoney = $data['caution_money'];
    //         $finalTotalAmount = $data['final_total_amount'];
    //         $accessoryHeadIds = $data['accessory_head_ids'];
    //         $waiverFeeUpdated = $data['waiver_fee_updated'];

    //         // Correct logging
    //         Log::info('Extracted guest_id', ['guest_id' => $guestId]);

    //         // // Extract values safely
    //         // $guestId          = $data['guest_id'] ?? null;
    //         // $accessoryHeadIds = $data['accessory_head_ids'] ?? [];

    //         // // Load guest with accessories filtered by accessory_head_ids
    //         // $guest = Guest::with(['accessories' => function ($query) use ($accessoryHeadIds) {
    //         //     if (!empty($accessoryHeadIds)) {
    //         //         $query->whereIn('accessory_head_id', $accessoryHeadIds);
    //         //     }
    //         // }])->find($guestId);

    //         // // Get accessories in variable (collection if found, empty otherwise)
    //         // $accessories = $guest ? $guest->accessories : collect();

    //         // // Log for debugging (context must be array)
    //         // Log::info('Guest amount fetched', [
    //         //     'guest_id'    => $guestId,
    //         //     'accessories' => $accessories->toArray(), // Convert collection to array for logging
    //         //     'data'        => $data, // Optional: log full response data
    //         // ]);

    //         // $guest = Guest::findOrFail($guestId);
    //         // $accessoryIds = $accessory_head_ids; // from logic
    //         // $accessories = Accessory::whereIn('id', $accessoryIds)->get();

    //         // // Map with qty (from pivot or default 1)
    //         // $accessoryData = $accessories->map(function ($acc) use ($guest) {
    //         //     $qty = $guest->accessories()
    //         //         ->where('accessory_id', $acc->id)
    //         //         ->first()
    //         //         ->pivot->qty ?? 1;

    //         //     return [
    //         //         'id'    => $acc->id,
    //         //         'name'  => $acc->name,
    //         //         'qty'   => $qty,
    //         //         'price' => $acc->price * $qty,
    //         //     ];
    //         // });


    //         // From your API response
    //         $data = $responseArray['data'] ?? [];

    //         $guestId = $data['guest_id'] ?? null;
    //         $accessoryHeadIds = $data['accessory_head_ids'] ?? [];

    //         Log::info('here', $accessoryHeadIds);
    //         // Load guest with accessories
    //         $guest = Guest::with(['accessories' => function ($query) use ($accessoryHeadIds) {
    //             $query->wherePivotIn('accessory_head_id', $accessoryHeadIds);
    //         }])->find($guestId);

    //         $accessories = $guest ? $guest->accessories : collect();


    //         //  Log::info('guest', $accessories);
    //         // Get accessories in variable
    //         // Log for debugging
    //         Log::info('Guest amount fetched', [
    //             'guest_id'    => $guestId,
    //             'accessories' => $accessories,
    //         ]);


    //         // Log properly




    //         // $feeData = $apiResponse['data'];
    //         //     Log::info('amount fetched', $feeData->toArray());
    //     } catch (Exception $ex) {
    //         Log::info('feeData');
    //     }
    //     // Log::info('amount fetched', $feeData);
    //     $response = [
    //         'success' => true,
    //         'status'  => $order->status,
    //         'txn_id'  => $order->transaction->txn_id ?? null,
    //         'amount'  => $order->transaction->txn_amount ?? null,
    //         'order_id' => $order->order_id,
    //         'guest'   => [
    //             'sc_n' => $order->guest->scholar_number ?? null,
    //             'name'  => $order->guest->name ?? null,
    //             'email' => $order->guest->email ?? null,
    //             'mobile' => $order->guest->mobile ?? null,
    //             'gender' => $order->guest->gender ?? null,
    //             'fathers_name' => $order->guest->fathers_name ?? null,
    //             'mothers_name' => $order->guest->mothers_name ?? null,
    //             'parent_contact' => $order->guest->parent_contact ?? null,
    //             'guardian_name' => $order->guest->local_guardian_name ?? null,
    //             'guardian_contact' => $order->guest->guardian_contact ?? null,
    //             'emergency_contact' => $order->guest->emergency_contact ?? null,
    //             'stay_duration' => $order->guest->month ?? null,
    //             'course' => $order->guest->course ?? null,

    //         ]
    //     ];

    //     Log::info($response);

    //     return response()->json($response);
    // }






















    // public function getStatus($orderId)
    // {
    //     $transaction = Order::where('order_id', $orderId)->first();

    //     if (!$transaction) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Transaction not found',
    //         ], 404);
    //     }

    //     return response()->json([
    //         'success'   => true,
    //         'status'    => $transaction->status,
    //         'txn_id'    => $transaction->transaction->txn_id,
    //         'order_id'  => $transaction->order_id,
    //         'handled'   => $transaction->status === 'TXN_SUCCESS'
    //     ]);
    // }

}

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

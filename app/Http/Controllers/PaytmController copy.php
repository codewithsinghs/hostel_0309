<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
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

            $status = $result['data']['STATUS'] ?? 'TXN_FAILURE';

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
                'order_id' => $orderId,
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

            $order->update(['status' => $status]);

            Log::info('Transaction recorded successfully', $transaction->toArray());

            Log::info('payment_status' . $status);
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


            // return response()->json([
            //     'success' => true,
            //     'message' => 'Payment successful.',
            //     'data' => [
            //         'payment_status' => $status,
            //         'order_id' => $order->id,
            //         // 'redirect_url' => $order->redirect_url,
            //         // 'user_data' => json_decode($order->user_data, true)
            //     ]
            // ]);


            // return redirect($order->redirect_url)->with($responseData);

            $message = 'Payment successful';

            // if ($request->expectsJson()) {
            //     return response()->json([
            //         'success' => true,
            //         'message' => 'Payment successful.',
            //         'data' => $responseData,
            //     ]);
            // }

            // return response()->json([
            //     'payment_status' => $status,
            //     'success' => true,
            //     'data'    => $responseData
            // ]);

            $recieptUrl = '/guest/payment/receipt';
            // return redirect($recieptUrl)->with([
            //     'payment_status' => $status,
            //     'success' => true,
            //     'data'    => $responseData
            // ]);

            // return redirect()->away($recieptUrl . '?' . http_build_query([
            $receiptUrl = '/guest/payment/receipt';
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
        $payment = Transaction::where('order_id', $request->order_id)->first();

        if (!$payment) {
            return response()->json(['success' => false, 'message' => 'Order not found']);
        }

        return response()->json([
            'success' => true,
            'status' => $payment->status,
            'txn_id' => $payment->txn_id,
            'amount' =>  $payment->txn_amount,
            'order_id' => $payment->order_id
        ]);
    }
}

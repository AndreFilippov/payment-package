<?php

namespace PaymentModule\Service;

use PaymentModule\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class OldPayService
{
    public static function sendRequestPay($order_id, $amount){
        $data = ['order_id' => $order_id, 'sum' => $amount];

        $response = Http::post(' https://old-pay.ru/api/create', $data);
        if($response->successful()) {
            $data = $response->json();
            if (isset($data['redirect_to'])) {
                return redirect($data['redirect_to']);
            }
        }
        return 'error';
    }

    public static function getRequestPay($request)
    {
        $order_id = $request->input('order_id');
        $transaction_id = $request->input('transaction_id');

        $payment = Payment::find($order_id);

        if($payment){
            if($payment->status == 'paid'){
                return false;
            }
            $secretKey = env('OldPayServiceKey');
            $response = Http::withHeaders(['X-Secret-Key' => $secretKey])->get('https://old-pay.ru/api/get-status', ['id' => $transaction_id]);
            if($response->successful()) {
                $data = $response->json();
                if ($data['order_id'] == $payment->order_Id) {
                    $payment->status = ($data['status'] == 'success') ? 'paid' : 'error';
                    $payment->amount = $data['sum'];
                    $payment->currency_code = 'RUB';
                    $payment->method = 'oldPay';
                    $payment->transaction_id = $transaction_id;
                    $payment->save();
                }
                return $payment;
            }
        }
        return false;
    }
}



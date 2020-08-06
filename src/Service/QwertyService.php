<?php

namespace PaymentModule\Service;

use PaymentModule\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class QwertyService
{

    public static function sendRequestPay($order_id, $amount, $currency){
        $data = ['order_id' => $order_id, 'sum' => $amount, 'currency' => $currency];
        $response = Http::post('https://qwerty.ru/pay', $data);

        if($response->successful()){
            return 'success';
        } elseif($response->failed()){
            return 'error';
        }
        return 'error';
    }

    public static function getRequestPay($request)
    {
        $order_id = $request->input('order_id');
        $amount = $request->input('sum');
        $currency = $request->input('currency');
        $transaction_id = $request->input('payment_id');
        $sign = $request->header('X-Signature');

        $payment = Payment::find($order_id);
        if($payment){
            if($payment->status == 'paid'){
                return false;
            }

            $secretKey = env('QwertyServiceKey');

            if($sign == $secretKey){
                $payment->transaction_id = $transaction_id;
                $payment->amount = $amount;
                $payment->status = 'paid';
                $payment->currency_code = $currency;
                $payment->method = 'qwerty';
                Payment::setBalance($payment->user_id, $amount);
            } else {
                $payment->status = 'error';
            }
            $payment->save();
            return $payment;
        }
        return false;
    }
}

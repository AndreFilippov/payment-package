<?php

namespace PaymentModule\Service;

use PaymentModule\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class XyzService
{
    public static function sendRequestPay($order_id, $amount, $name = false){
        $data = ['order_id' => $order_id, 'sum' => $amount];
        if($name) {
            $data['name'] = $name;
        }
        $response = Http::get('https://xyz-payment.ru/pay', $data);
        if($response->successful()) {
            return 'success';
        } else if($response->failed()) {
            return 'error';
        }
        return 'error';
    }

    public static function getRequestPay($request){
        $order_id = $request->input('order_id');
        $amount = $request->input('sum');
        $name = $request->input('name');
        $transaction_id = $request->input('transaction_id');
        $sign = $request->input('sign');

        $secretKey = env('XyzServiceKey');

        $payment = Payment::find($order_id);
        if($payment){
            if($payment->status == 'paid'){
                return false;
            }

            if($sign == $secretKey){
                $payment->transaction_id = $transaction_id;
                $payment->name = $name;
                $payment->amount = $amount;
                $payment->status = 'paid';
                $payment->currency_code = 'RUB';
                $payment->method = 'XYZ';
                Payment::setBalance($payment->user_id, $amount);
            } else {
                $payment->comment = 'Неверный ключ';
                $payment->status = 'error';
            }
            $payment->save();
            return $payment;
        }
        return false;
    }
}

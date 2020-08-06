<?php

namespace PaymentModule\Http\Controllers;

use App\Http\Controllers\Controller;
use PaymentModule\Models\Payment;
use PaymentModule\Service\OldPayService;
use PaymentModule\Service\QwertyService;
use PaymentModule\Service\XyzService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function indexAction()
    {
        if(!Auth::check()){
           return redirect('/');
        }
        return view('payment::form_balance',['user' => Auth::user()]);
    }

    /**
     * @param Request $request
     *
     * Конвертирование валюты
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRateCurrencyAction(Request $request){
        $code = $request->input('code');
        $amount = $request->input('amount');
        $url = 'https://cash.rbc.ru/cash/json/converter_currency_rate';
        $url .= '?'.http_build_query(['currency_from' => $code, 'currency_to' => 'RUR', 'source' => 'cbrf', 'sum' => $amount]);
        $result = Http::get($url);
        $data = $result->json()['data'] ?? false;
        if($data && isset($data['sum_result'])){
            return response()->json(['status' => true, 'rate_amount' => $data['sum_result']]);
        }
        return response()->json(['status' => false]);
    }

    /**
     * @param Request $request
     *
     * Отправка запроса на пополнене баланса в сервисе
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendPayAction(Request $request){
        if(!Auth::check()){
            return response()->json(['message' => 'Неавторизованный пользователь'],403);
        }
        $code = $request->input('code') ?: 'RUB';
        $amount = $request->input('amount');
        $method = $request->input('method');

        if($code && $amount && $method){
            if(in_array($method,['xyz','qwerty','oldpay'])){
                $payment = Payment::create(['user_id' => Auth::id()]);
                switch ($method){
                    case 'xyz':
                        $send = XyzService::sendRequestPay($payment->id, $amount, $request->input('name',''));
                        break;
                    case 'qwerty':
                        $send = QwertyService::sendRequestPay($payment->id, $amount, $request->input('currency'));
                        break;
                    case 'oldpay':
                        $send = OldPayService::sendRequestPay($payment->id, $amount);
                        break;
                    default:
                        return response('error');

                }
                return response($send);
            }
        } else {
            return response('error');
        }
    }
}

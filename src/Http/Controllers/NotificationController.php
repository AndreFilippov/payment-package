<?php

namespace PaymentModule\Http\Controllers;

use App\Http\Controllers\Controller;
use PaymentModule\Service\OldPayService;
use PaymentModule\Payment\Service\QwertyService;
use PaymentModule\Payment\Service\XyzService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class NotificationController extends Controller
{
    public function xyzPaymentAction()
    {
        $request = Request();
        XyzService::getRequestPay($request);
        return false;
    }

    public function qwertyPaymentAction()
    {
        $request = Request();
        QwertyService::getRequestPay($request);
        return false;
    }

    public function oldPayPaymentAction()
    {
        $request = Request();
        OldPayService::getRequestPay($request);
        return false;
    }
}

<?php

Route::group(['namespace' => 'PaymentModule\Http\Controllers'], function (){
    Route::group(['middleware' => ['web','auth']], function (){
        Route::get('paymentmodule/getRateCurrency', 'PaymentController@getRateCurrencyAction');
        Route::post('paymentmodule/sendPay', 'PaymentController@sendPayAction');

    });
    Route::post('paymentmodule/notification/xyz','NotificationController@xyzPaymentAction');
    Route::post('paymentmodule/notification/qwerty','NotificationController@qwertyPaymentAction');
    Route::post('paymentmodule/notification/oldpay','NotificationController@oldPayPaymentAction');
});

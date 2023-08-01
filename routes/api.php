<?php

use App\Http\Controllers\Api\Auth\ClientController;
use App\Http\Controllers\Api\Auth\ForgetPasswordController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\VerifyEmailController;
use App\Http\Controllers\Api\ContactUsController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\HistoryController;
use App\Http\Controllers\Api\PagesController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\SupportController;
use App\Http\Controllers\Api\WalletController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Twilio\Rest\Client;


Route::middleware(['throttle:9998,1'])->group(function(){

    Route::apiResource('/login' , LoginController::class);
    Route::apiResource('/register' , RegisterController::class);


    Route::get('/verify/{link}' , VerifyEmailController::class);

    Route::apiResource('/password' , ForgetPasswordController::class);

    Route::post('/pay/complete' , [PaymentController::class , 'complete']);


    Route::apiResource('/contact_us' , ContactUsController::class);
    Route::apiResource('/about_us' , PagesController::class);
    Route::get('/pay/{link}' , [PaymentController::class , 'pay'])->name('pay');
    Route::get('/pay_with_link/{link}' , [PaymentController::class , '_pay']);

    Route::get('/resend/{payment_id}' , [PaymentController::class , 'resend']);

    Route::middleware(['auth:api'])->group(function(){

        Route::apiResource('/payment' , PaymentController::class);
        Route::apiResource('/customer' , CustomerController::class);
        Route::apiResource('/support' , SupportController::class);
        Route::apiResource('/wallet' , WalletController::class);

        Route::get('/dashboard' , [PaymentController::class , 'dashboard']);
        Route::get('/withdrawal/{wallet_id}' , [PaymentController::class , 'withdrawal']);


        Route::apiResource('/setting' , ClientController::class);
        Route::apiResource('/history' , HistoryController::class);

        Route::post('/set_password' , [ForgetPasswordController::class , 'set_password']);

    });

    Route::post('/support/admin' , [SupportController::class , 'store_admin'])->name('support_store_admin');

    Route::get('send_test_mail' , function(){
        Mail::send('reminder', [
            'data' => [
                "message" => "sdasdadsads"
            ],
        ], function($message){
            $message->from('info@paysweet.net');
            $message->to('naeimsafaee1412@gmail.com')->subject('SweetPay reminder!');
        });
    });

    Route::get('send_test_sms' , function(){

        $twilioConfig = config('services.twilio');
        $accountSid = $twilioConfig['twilio_account_sid'];
        $authToken = $twilioConfig['twilio_auth_token'];

        $twiilio = new Client($accountSid, $authToken);

        $twiilio->messages->create("+989358548353", [
            "from" => $twilioConfig['twilio_number'] ,
            "body" => "sadsdas",
        ]);
    });

});


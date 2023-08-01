<?php

namespace App\AppointmentReminders;

use App\Models\Appointment;
use App\Models\Payment;
use Illuminate\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Twilio\Rest\Client;
use Twilio\TwiML\Voice\Pay;

class AppointmentReminder{

    function __construct(){

        $now = Carbon::now();
        $inTenMinutes = Carbon::now()->addMinutes(10);

        $this->appointments = Appointment::query()->where('notificationTime', '>=', $now)->where('notificationTime', '<=', $inTenMinutes)->get();

        $twilioConfig = config('services.twilio');
        $accountSid = $twilioConfig['twilio_account_sid'];
        $authToken = $twilioConfig['twilio_auth_token'];
        $this->sendingNumber = $twilioConfig['twilio_number'];

        $this->twilioClient = new Client($accountSid, $authToken);
    }

    public function sendReminders(){
        $this->appointments->each(function($appointment){
            $recipientName = $appointment->name;
            //->subMinutes($appointment->timezoneOffset)
            $time = Carbon::parse($appointment->when, 'UTC')->format('g:i a');

            $payment = Payment::query()->find($appointment->payment_id);

            $this->_sendMessage($appointment->send_to, $payment, $appointment->is_sms);
        });
    }


    private function _sendMessage($send_to, $payment, $is_sms){

        if($is_sms){

            $message = get_template_message($payment , true);

            $this->twilioClient->messages->create($send_to, [
                "from" => $this->sendingNumber,
                "body" => str_replace("<br>" , "" ,$message),
            ]);
        } else {

            $message = get_template_message($payment , false);

            Mail::send('reminder', [
                'data' => [
                    "message" => $message
                ],
            ], function($message) use ($send_to){
                $message->from('info@paysweet.net');
                $message->to($send_to)->subject('SweetPay reminder!');
            });
        }

    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerPaginationCollection;
use App\Http\Resources\PaymentCollection;
use App\Http\Resources\PaymentPaginationCollection;
use App\Http\Resources\PaymentResource;
use App\Models\Appointment;
use App\Models\Customer;
use App\Models\History;
use App\Models\Payment;
use App\Models\Reminder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Stripe\StripeClient;
use Twilio\Exceptions\ConfigurationException;
use Twilio\Rest\Client;
use Illuminate\Database\Eloquent\Builder;

class PaymentController extends Controller{

    public function index(Request $request){
        $client_id = auth()->guard('api')->user()->id;

        $payments = Payment::query()->where('client_id', $client_id);

        if($request->has('search')){
            $payments = $payments->where('amount', 'LIKE', "%$request->search%");
            $payments = $payments->orWhereHas('customer', function(Builder $query) use ($request){
                $query->where('name', 'like', "%$request->search%")->orWhere('email', 'like', "%$request->search%")->orWhere('phone', 'like', "%$request->search%");
            });
        }

        $per_page = 10;
        if($request->per_page)
            $per_page = $request->per_page;

        $payments = $payments->paginate($per_page);

        return _response(new PaymentPaginationCollection($payments));
    }

    public function show($id){
        $client_id = auth()->guard('api')->user()->id;

        $payment = Payment::query()->findOrFail($id);
        if($payment->client_id != $client_id)
            throw ValidationException::withMessages(['payment' => 'you do not have permission to this payment']);

        return new PaymentResource($payment);
    }

    public function _pay($link){

        $payment = Payment::query()->where('link', $link)->firstOrFail();

        return new PaymentResource($payment);
    }

    public function store(Request $request){
        Validator::make($request->all(), [
            'invoice' => ['required', 'string'],
            'amount' => ['required', 'string'],
            'customer_id' => ['string', 'exists:customers,id'],
            'customer_name' => ['string'],
            'customer_phone' => ['string'],
            'customer_email' => ['string', 'email:rfc,dns'],
            'address' => ['required', 'string'],
            'pdf_file' => ['file', 'mimes:pdf'],
        ])->validate();

        $fileName = "";

        if($request->has('pdf_file')){
            $file = $request->pdf_file;

            $fileName = 'files/' . time() . '-' . rand() . '.' . $file->getClientOriginalExtension();

            Storage::disk('public')->put($fileName, file_get_contents($file));
        }

        $client_id = auth()->guard('api')->user()->id;

        if($request->customer_name && strlen($request->customer_name) > 0){

            if(!$request->customer_phone || !$request->customer_email)
                throw ValidationException::withMessages(['customer_id' => 'customer phone and email are required']);

            $client_id = auth()->guard('api')->user()->id;

            $customer = Customer::query()->create([
                'name' => $request->customer_name,
                'email' => $request->customer_email,
                'phone' => $request->customer_phone,
                'client_id' => $client_id,
            ]);

            $customer_id = $customer->id;

        } elseif($request->customer_id) {

            $customer = Customer::query()->find($request->customer_id);
            if(!$customer){
                throw ValidationException::withMessages(['customer_id' => 'customer id does not exist']);
            }

            $customer_id = $customer->id;

        } else {
            throw ValidationException::withMessages(['customer_id' => 'customer id or new customer is required']);
        }

        $payment = Payment::query()->create([
            'invoice' => $request->invoice,
            'amount' => $request->amount,
            'customer_id' => $customer_id,
            'address' => $request->address,
            'pdf_file' => $fileName,
            'link' => $this->generateRandomString(14),
            'client_id' => $client_id,
        ]);

        $this->resend($payment->id);

        $reminder = Reminder::query()->where('client_id', $client_id)->first();
        if($reminder){

            if($reminder->is_3_day){
                $when = Carbon::now()->addDays(3);

                if($reminder->is_sms){
                    Appointment::query()->create([
                        'name' => $payment->customer->name,
                        'send_to' => $payment->customer->phone,
                        'when' => $when,
                        'notificationTime' => Carbon::parse($when),
                        'is_sms' => true,
                        'payment_id' => $payment->id,
                    ]);
                }

                if($reminder->is_email){
                    Appointment::query()->create([
                        'name' => $payment->customer->name,
                        'send_to' => $payment->customer->email,
                        'when' => $when,
                        'notificationTime' => Carbon::parse($when),
                        'is_sms' => false,
                        'payment_id' => $payment->id,
                    ]);
                }
            }

            if($reminder->is_7_day){
                $when = Carbon::now()->addDays(7);

                if($reminder->is_sms){
                    Appointment::query()->create([
                        'name' => $payment->customer->name,
                        'send_to' => $payment->customer->phone,
                        'when' => $when,
                        'notificationTime' => Carbon::parse($when),
                        'is_sms' => true,
                        'payment_id' => $payment->id,
                    ]);
                }

                if($reminder->is_email){
                    Appointment::query()->create([
                        'name' => $payment->customer->name,
                        'send_to' => $payment->customer->email,
                        'when' => $when,
                        'notificationTime' => Carbon::parse($when),
                        'is_sms' => false,
                        'payment_id' => $payment->id,
                    ]);
                }
            }

        }

        return new PaymentResource($payment);
    }

    public function dashboard(){
        $client_id = auth()->guard('api')->user()->id;

        $last_3 = Payment::query()->where('client_id', $client_id)->orderByDesc('created_at')->limit(3)->get();
        $wallet = Payment::query()->where('client_id', $client_id)->where('status', 0)->sum('amount');

        $last_30 = Payment::query()->where([
            'client_id' => $client_id,
            'status' => 0,
        ])->whereDate('paid_at', '>', Carbon::now()->subDays(30))->orderByDesc('paid_at')->get();

        return _response([
            "last_3" => new PaymentCollection($last_3),
            "last_30" => (new PaymentCollection($last_30))->collection->groupBy(function($date){
                return Carbon::parse($date->paid_at)->format('Y-m-d');
            }),
            "wallet" => $wallet,
        ]);
    }

    public function withdrawal($wallet_id){
        $client = auth()->guard('api')->user();

        $client->wallet_id = $wallet_id;
        $client->requested_at = Carbon::now();
        $client->save();

        return _response("ok");
    }

    public function pay($link){

        $payment = Payment::query()->where('link', $link)->firstOrFail();

        if($payment->status == 0){
            throw ValidationException::withMessages(['payment' => 'this payment had paid before!']);
        }
        //        Stripe::setApiKey(config('constant.STRIPE_SECRET'));

        /*$intent = PaymentIntent::create([
            'amount' => $payment->amount,
            'currency' => 'eur',
            'payment_method_types' => ['ideal'],
        ]);*/

        $stripe = new StripeClient(config('constant.STRIPE_SECRET'));
        $intent = $stripe->paymentIntents->create([
            'amount' => $payment->amount * 100,
            'currency' => 'eur',
            'payment_method_types' => ['ideal'],
        ]);

        /*$customer = \Stripe\Customer::create();

        $setup_intent = \Stripe\SetupIntent::create([
            'payment_method_types' => ['ideal'],
            'customer' => $customer->id,
        ]);*/

        //        $secret = $intent->client_secret;

        $payment->secret = $intent->id;
        $payment->save();

        History::query()->create([
            'payment_id' => $payment->id,
            'customer_id' => $payment->customer_id,
        ]);

        return _response([
            "payment" => new PaymentResource($payment),
            'client_secret' => $intent,
        ]);

    }

    public function complete(Request $request){

        $payment = Payment::query()->where('secret', $request->intent)->firstOrFail();

        $payment->status = 0;
        $payment->paid_at = Carbon::now();
        $payment->save();

        $history = History::query()->where('payment_id', $payment->id)->firstOrFail();
        $history->has_paid = true;
        $history->save();


        return _response(new PaymentResource($payment));
    }

    public function resend($payment_id){
        $client_id = auth()->guard('api')->user()->id;

        $payment = Payment::query()->findOrFail($payment_id);

        if($payment->client_id != $client_id)
            throw ValidationException::withMessages(['payment' => 'you do not have permission to this payment']);

        Mail::send('reminder', [
            'data' => [
                "message" => get_template_message($payment, false),
            ],
        ], function($message) use ($payment){
            $message->from('info@paysweet.net');
            $message->to($payment->customer->email)->subject('SweetPay reminder!');
        });


        $twilioConfig = config('services.twilio');
        $accountSid = $twilioConfig['twilio_account_sid'];
        $authToken = $twilioConfig['twilio_auth_token'];

        $twiilio = new Client($accountSid, $authToken);

        try {
            $twiilio->messages->create($payment->customer->phone, [
                "from" => $twilioConfig['twilio_number'],
                "body" => str_replace("<br>", "", get_template_message($payment, true)),
            ]);
        } catch(\Exception $exception){
            throw ValidationException::withMessages(['sms' => $exception->getMessage()]);
        }


        return _response(null, "ok");
    }

    function generateRandomString($length = 10){
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for($i = 0; $i < $length; $i++){
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

}

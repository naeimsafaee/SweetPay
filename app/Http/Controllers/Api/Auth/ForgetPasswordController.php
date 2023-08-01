<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\LoginCollection;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ForgetPasswordController extends Controller{

    public function index(Request $request){
        Validator::make($request->all(), [
            'email' => ['required', 'string' , 'email:rfc,dns', 'exists:clients,email'],
        ])->validate();

        $link = $this->generateRandomString();

        $client = Client::query()->where('email' , $request->email)->firstOrFail();
        $client->code = $link;
        $client->save();

         Mail::send('forget_password', [
            "data" => [
                'link' => $link,
            ]
        ], function($message) use($request) {
            $message->from('auth@paysweet.net');
            $message->to($request->email)->subject('SweetPay forget password requested!');
        });

        return _response("ok");
    }

    public function set_password(Request $request){
        Validator::make($request->all(), [
            'password' => ['required', 'string' , 'min:8'],
            'old_password' => ['required', 'string' , 'min:8'],
        ])->validate();

        $client = Client::query()->findOrFail(auth()->guard('api')->user()->id);

        if(!password_verify($request->old_password , $client->password)){
            throw ValidationException::withMessages(['password' => 'Your auth data is wrong!']);
        }

        $client->password = Hash::make($request->password);
        $client->save();

        $token = $client->createToken('TokenForNaeim')->accessToken;

        return _response($token);
    }

    public function update(Request $request, $id){
        Validator::make($request->all(), [
            'password' => ['required', 'string' , 'min:8'],
            'link' => ['required', 'string' , 'exists:clients,code'],
        ])->validate();

        $client = Client::query()->where('code' , $request->link)->firstOrFail();
        $client->password = Hash::make($request->password);
        $client->save();

        $token = $client->createToken('TokenForNaeim')->accessToken;

        return _response($token);
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

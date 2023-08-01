<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\LoginCollection;
use App\Mail\VerifyEmail;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller{

    public function store(Request $request){
        Validator::make($request->all(), [
            'email' => ['required', 'string', 'email:rfc,dns', 'unique:clients,email'],
            'username' => ['required', 'string', 'unique:clients,username'],
            'password' => ['required', 'string', 'min:8'],
        ])->validate();

        $link = $this->generateRandomString();

        $client = Client::query()->create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'code' => $link,
        ]);

       /* Mail::send('verify_mail', [
            'link' => $link,
        ], function($message) use($request) {
            $message->from('paysweet@paysweet.net');
            $message->to($request->email)->subject('Verify email');
        });*/

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

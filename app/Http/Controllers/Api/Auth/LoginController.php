<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\LoginCollection;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller{

    public function store(Request $request){
        Validator::make($request->all(), [
            'username' => ['required', 'string' , 'exists:clients,username'],
            'password' => ['required', 'string'],
        ])->validate();

        $client = Client::query()->where('username' , $request->username)->firstOrFail();

        if(!password_verify($request->password , $client->password)){
            throw ValidationException::withMessages(['password' => 'Your auth data is wrong!']);
        }

        $token = $client->createToken('TokenForNaeim')->accessToken;

        return _response($token);
    }

}

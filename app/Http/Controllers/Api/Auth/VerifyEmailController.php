<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\LoginCollection;
use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller{

    public function __invoke($link){

        $client = Client::query()->where('code' , $link)->firstOrFail();
        $client->email_verified_at = Carbon::now();
        $client->save();

        $token = $client->createToken('TokenForNaeim')->accessToken;

        return _response($token);
    }


}

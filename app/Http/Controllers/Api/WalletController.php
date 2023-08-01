<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentCollection;
use App\Http\Resources\WalletCollection;
use App\Http\Resources\WalletResource;
use App\Models\Payment;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class WalletController extends Controller{

    public function index(){
        $client_id = auth()->guard('api')->user()->id;

        $wallets = Wallet::query()->where('client_id' , $client_id)->get();

        return new WalletCollection($wallets);
    }

    public function show($id){
        $client_id = auth()->guard('api')->user()->id;

        $wallet = Wallet::query()->findOrFail($id);
        if($wallet->client_id != $client_id)
            throw ValidationException::withMessages(['wallet' => 'you do not have permission to this wallet']);

        return new WalletResource($wallet);
    }

    public function store(Request $request){
        Validator::make($request->all(), [
            'name' => ['required', 'string' ],
            'company_name' => ['required', 'string' ],
            'bank_name' => ['required', 'string' ],
            'registration_number' => ['required', 'string' ],
            'bic_number' => ['required', 'string'],
            'iban_number' => ['required', 'string'],
            'vat_number' => ['required', 'string'],
            'address' => ['required'],
        ])->validate();

        $client_id = auth()->guard('api')->user()->id;

        $wallet = Wallet::query()->create([
            'name' => $request->name,
            'company_name' => $request->company_name,
            'bank_name' => $request->bank_name,
            'registration_number' => $request->registration_number,
            'bic_number' => $request->bic_number,
            'iban_number' => $request->iban_number,
            'vat_number' => $request->vat_number,
            'address' => $request->address,
            'client_id' => $client_id,
        ]);

        return new WalletResource($wallet);
    }

}

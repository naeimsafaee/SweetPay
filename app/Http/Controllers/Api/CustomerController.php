<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerCollection;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\CustomerPaginationCollection;
use App\Http\Resources\PaymentCollection;
use App\Models\Customer;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CustomerController extends Controller{

    public function index(Request $request){
        $client_id = auth()->guard('api')->user()->id;

        $customers = Customer::query()->where('client_id', $client_id);

        if($request->has('search'))
            $customers = $customers->where('name' , 'LIKE' , "%$request->search%");

        $per_page = 10;
        if($request->per_page)
            $per_page = $request->per_page;

        $customers = $customers->paginate($per_page);

        return _response(new CustomerPaginationCollection($customers));
    }

    public function show($id){
        $client_id = auth()->guard('api')->user()->id;

        $customer = Customer::query()->findOrFail($id);
        if($customer->client_id != $client_id)
            throw ValidationException::withMessages(['customer' => 'you do not have permission to this customer']);

        return _response(new CustomerResource($customer));
    }

    public function store(Request $request){
        Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'email' => ['required', 'string', 'email:rfc,dns'],
            'phone' => ['required', 'string'],
        ])->validate();
        //, 'regex:/^((71)|(73)|(77))[0-9]{7}/'

        $client_id = auth()->guard('api')->user()->id;

        $customer = Customer::query()->create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'client_id' => $client_id,
        ]);

        return _response(new CustomerResource($customer));
    }

    public function update(Request $request , $id){
        Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'email' => ['required', 'string', 'email:rfc,dns'],
            'phone' => ['required', 'string'],
        ])->validate();

        $client_id = auth()->guard('api')->user()->id;

        $customer = Customer::query()->findOrFail($id);
        if($customer->client_id != $client_id)
            throw ValidationException::withMessages(['customer' => 'you do not have permission to this customer']);

        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->phone = $request->phone;
        $customer->save();

        return _response(new CustomerResource($customer));
    }

    public function destory($id){
        $client_id = auth()->guard('api')->user()->id;

        $customer = Customer::query()->findOrFail($id);
        if($customer->client_id != $client_id)
            throw ValidationException::withMessages(['customer' => 'you do not have permission to this customer']);

        $customer->delete();

        return _response("" , "ok");
    }


}

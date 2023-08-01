<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SupportPaginationCollection;
use App\Http\Resources\SupportResource;
use App\Models\Payment;
use App\Models\Support;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class SupportController extends Controller{

    public function index(){
        $client_id = auth()->guard('api')->user()->id;

        $supports = Support::query()->where('client_id', $client_id)->paginate(10);

        return _response(new SupportPaginationCollection($supports));
    }

    public function store(Request $request){
        Validator::make($request->all(), [
            'text' => ['required', 'string'],
        ])->validate();

        $client_id = auth()->guard('api')->user()->id;

        $support = Support::query()->create([
            'client_id' => $client_id,
            'text' => $request->text,
            'is_admin' => false,
        ]);

        return _response(new SupportResource($support));
    }

    public function store_admin(Request $request){
        Validator::make($request->all(), [
            'text' => ['required', 'string'],
            'client_id' => ['required', 'exists:clients,id'],
        ])->validate();

        $client_id = $request->client_id;

        $support = Support::query()->create([
            'client_id' => $client_id,
            'text' => $request->text,
            'is_admin' => true,
        ]);

        return _response("ok");
    }

    public function show($id){
        $client_id = auth()->guard('api')->user()->id;

        $support = Support::query()->findOrFail($id);

        if($support->client_id != $client_id)
            throw ValidationException::withMessages(['support' => 'you do not have permission to this support']);

        return _response(new SupportResource($support));
    }

}

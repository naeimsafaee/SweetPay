<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContactUsCollection;
use App\Http\Resources\ContactUsResource;
use App\Models\ContactUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactUsController extends Controller{

    public function index(){
        $contact_us = ContactUs::all();

        return _response(new ContactUsCollection($contact_us));
    }

    public function store(Request $request){
        Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'email' => ['required', 'string', 'email:rfc,dns'],
            'text' => ['required', 'string'],
        ])->validate();

        $contact_us = ContactUs::query()->create([
           "name" => $request->name,
           "email" => $request->email,
           "text" => $request->text,
        ]);

        return _response(new ContactUsResource($contact_us));
    }

    public function show($id){

        $contact_us = ContactUs::query()->findOrFail($id);

        return _response(new ContactUsResource($contact_us));
    }

    public function update(Request $request, $id){
        //
    }

    public function destroy($id){
        //
    }
}

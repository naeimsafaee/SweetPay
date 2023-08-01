<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReminderCollection;
use App\Http\Resources\ReminderResource;
use App\Models\Client;
use App\Models\Reminder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller{

    public function index(){
        $client_id = auth()->guard('api')->user()->id;

        $reminders = Reminder::query()->where([
            'client_id' => $client_id,
        ])->get();

        return _response(new ReminderCollection($reminders));
    }

    public function store(Request $request){

        Validator::make($request->all(), [
            'is_3_days' => ['required', 'bool'],
            'is_7_days' => ['required', 'bool'],
            'template' => ['required', 'string'],
            'is_sms' => ['required', 'bool'],
            'is_email' => ['required', 'bool'],
        ])->validate();

        $client_id = auth()->guard('api')->user()->id;

        $reminder = Reminder::query()->updateOrCreate([
            'client_id' => $client_id,
        ], [
            'client_id' => $client_id,
            'is_3_day' => $request->is_3_days,
            'is_7_day' => $request->is_7_days,
            'template' => $request->template,
            'is_sms' => $request->is_sms,
            'is_email' => $request->is_email,
        ]);

        return _response(new ReminderResource($reminder));
    }

    public function show($id){
        //
    }

    public function update(Request $request, $id){
        //
    }

    public function destroy($id){
        //
    }
}

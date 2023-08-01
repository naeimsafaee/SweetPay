<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\HistoryCollection;
use App\Models\History;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class HistoryController extends Controller{

    public function index(){
        $client_id = auth()->guard('api')->user()->id;

        $history = History::query()->whereHas('payment' , function(Builder $query) use($client_id) {
            $query->where('client_id' , $client_id);
        })->get();

        return _response(new HistoryCollection($history));
    }

    public function show($id){

        $history = History::query()->whereHas('payment' , function(Builder $query) use($id) {
            $query->where('customer_id' , $id);
        })->get();

        return _response(new HistoryCollection($history));
    }

}

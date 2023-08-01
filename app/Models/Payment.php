<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Facades\Voyager;

class Payment extends Model{
    use HasFactory;

    protected $fillable = [
        'name',
        'invoice',
        'amount',
        'customer_id',
        'address',
        'pdf_file',
        'link',
        'secret',
        'client_id',
    ];

    public function customer(){
        return $this->belongsTo(Customer::class);
    }

    public function setPdfFileAttribute($value){
        $this->attributes['pdf_file'] = Voyager::image($value);
//        $this->pdf_file = Voyager::image($value);
    }


}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model{
    use HasFactory;

    protected $fillable = [
        'payment_id',
        'customer_id',
    ];

    public function payment(){
        return $this->belongsTo(Payment::class);
    }

    public function customer(){
        return $this->belongsTo(Customer::class);
    }


}

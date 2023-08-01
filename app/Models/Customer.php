<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model{
    use HasFactory , SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'client_id',
    ];

    protected $appends = [
        'total_payment',
        'total_invoices	'
    ];

    public function getTotalPaymentAttribute(){
        return $this->hasMany(Payment::class)->where('status' , 0)->sum('amount');
    }

    public function getTotalInvoicesAttribute(){
        return $this->hasMany(Payment::class)->where('status' , 1)->sum('amount');
    }

}

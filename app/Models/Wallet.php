<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model{
    use HasFactory;

    protected $fillable = [
        'name',
        'company_name',
        'bank_name',
        'registration_number',
        'bic_number',
        'iban_number',
        'vat_number',
        'address',
        'client_id',
    ];

}

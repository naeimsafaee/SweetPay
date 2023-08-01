<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reminder extends Model{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'is_3_day',
        'is_7_day',
        'template',
        'is_sms',
        'is_email',
    ];

    public function client(){
        return $this->belongsTo(Client::class);
    }


}

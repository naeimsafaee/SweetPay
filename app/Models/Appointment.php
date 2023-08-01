<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model{
    use HasFactory;

    protected $dates = ['when', 'notificationTime'];

    protected $fillable = [
        'name',
        'send_to',
        'when',
        'notificationTime',
        'is_sms',
        'payment_id'
    ];

}

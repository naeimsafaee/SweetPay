<?php

return [
    'payment_statuses' => [
        0 => 'paid',
        1 => 'unpaid',
        2 => 'pending'
    ],
    "STRIPE_SECRET" => env('STRIPE_SECRET'),
    "APP_VUE_URL" => env('APP_VUE_URL')


];

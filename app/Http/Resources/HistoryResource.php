<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HistoryResource extends JsonResource{

    public function toArray($request){
        return [
            'payment' => new PaymentResource($this->payment),
            'customer' => new CustomerResource($this->customer),
            'has_paid' => $this->has_paid == true,
        ];
    }
}

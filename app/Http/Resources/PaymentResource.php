<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource{

    public function toArray($request){
        if(strlen($this->status) === 0)
            $this->status = 1;

        return [
            'id' => $this->id,
            'invoice' => $this->invoice,
            'amount' => $this->amount,
            'customer' => new CustomerResource($this->customer),
            'address' => $this->address,
            'pdf_file' => $this->pdf_file,
            'link' => $this->link,
            'status' => config('constant.payment_statuses')[$this->status],
            'created_at' => $this->created_at,
        ];
    }
}

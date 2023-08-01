<?php

namespace App\Http\Resources;



use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource{

    public function toArray($request){
        return [
            "id" => $this->id,
            "name" => $this->name,
            "email" => $this->email,
            "phone" => $this->phone,
            "total_payment" => $this->total_payment,
            "total_invoices" => $this->total_invoices,
        ];
    }
}

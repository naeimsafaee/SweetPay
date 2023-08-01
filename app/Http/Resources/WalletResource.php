<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WalletResource extends JsonResource{

    public function toArray($request){
        return [
            "id" => $this->id,
            "name" => $this->name,
            "company_name" => $this->company_name,
            "bank_name" => $this->bank_name,
            "registration_number" => $this->registration_number,
            "bic_number" => $this->bic_number,
            "iban_number" => $this->iban_number,
            "vat_number" => $this->vat_number,
            "address" => $this->address,
            "created_at" => $this->created_at,
        ];
    }
}

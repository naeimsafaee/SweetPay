<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SupportResource extends JsonResource{

    public function toArray($request){
        return [
            "id" => $this->id,
            "text" => $this->text,
            "client" => [
                "id" => $this->client->id,
                "username" => $this->client->username,
            ],
            "is_admin" => $this->is_admin == 1,
            "created_at" => $this->created_at,
        ];
    }
}

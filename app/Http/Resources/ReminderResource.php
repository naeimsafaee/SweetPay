<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReminderResource extends JsonResource{

    public function toArray($request){
        return [
            "id" => $this->id,
            "template" => $this->template ?? "",
            "client" => [
                "id" => $this->client->id,
                "username" => $this->client->username,
            ],
            "is_sms" => $this->is_sms == 1,
            "is_email" => $this->is_email == 1,
            "is_7_day" => $this->is_7_day == 1,
            "is_3_day" => $this->is_3_day == 1,
            "created_at" => $this->created_at,
        ];
    }
}

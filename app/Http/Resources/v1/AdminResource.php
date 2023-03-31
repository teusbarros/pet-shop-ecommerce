<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\User */

final class AdminResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     *
     * @extends JsonResource<User>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'address' => $this->address,
            'phone_number' => $this->phone_number,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
            'token' => $this->token()->first()->unique_id, // @phpstan-ignore-line
        ];
    }
}

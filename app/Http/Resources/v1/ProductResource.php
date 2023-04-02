<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Product */
class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'category_uuid' => $this->category_uuid,
            'title' => $this->title,
            'uuid' => $this->uuid,
            'price' => $this->price,
            'description' => $this->description,
            'metadata' => json_decode($this->metadata),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'category' => new CategoryResource($this->category),
            'brand' => new BrandResource($this->brand),
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WarehouseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "warehouse" => [
                "id" => $this->id,
                "name" => $this->name,
                "location" => $this->location,
                "total_items" => $this->items->count() 
            ],
            "items" => $this->items->map(function ($item) {
                return [
                    "id" => $item->item->id, 
                    "name" => $item->item->name,
                    "stock" => $item->stock,
                    "created_at" => $item->created_at,
                ];
            })
        ];
    }
}

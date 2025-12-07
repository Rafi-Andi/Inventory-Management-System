<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "supplier" => $this->supplier,
            "from_warehouse" => $this->from_warehouse,
            "to_warehouse" => $this->to_warehouse,
            "user" => $this->user,
            "status" => $this->status,
            "items_count" => $this->whenLoaded('po_items', function(){
                return $this->po_items->count();
            }),
            "created_at" => $this->created_at
        ];
    }
}

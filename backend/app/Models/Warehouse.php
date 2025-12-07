<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $guarded = ['id'];

    public function total_items()
    {
        return $this->belongsToMany(Item::class, 'warehouse_items', 'warehouse_id', 'item_id');
    }

    public function items(){
        return $this->hasMany(WarehouseItem::class);
    }

    public function item_warehouses(){
        return $this->belongsToMany(Item::class, 'warehouse_items', 'warehouse_id', 'item_id');
    }
}

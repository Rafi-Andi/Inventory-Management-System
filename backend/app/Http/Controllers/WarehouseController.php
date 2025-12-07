<?php

namespace App\Http\Controllers;

use App\Http\Resources\WarehouseResource;
use Exception;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $warehouses = Warehouse::withCount('items')->get();
            return response()->json([
                "data" =>
                $warehouses
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "message" => "error",
                "errors" => $e->getMessage()
            ], 500);
        }
    }

    public function warehouse_stock(Request $request)
    {
        try {
            $user = $request->user();
            $warehouses = Warehouse::with('items.item')->where('id', $user->warehouse_id)->get();
            return response()->json([
                "data" => WarehouseResource::collection($warehouses)
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "message" => "error",
                "errors" => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Warehouse $warehouse)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Warehouse $warehouse)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Warehouse $warehouse)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Warehouse $warehouse)
    {
        //
    }
}

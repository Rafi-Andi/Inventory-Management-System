<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Administrator;
use App\Models\Warehouse;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Item::query();

            if ($request->search) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }

            if ($request->category_id) {
                $query->where('category_id', $request->category_id);
            }

            $items = $query->with('category')->paginate($request->per_page);
            return response()->json([
                "message" => "success",
                "data" => $items
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
        try {
            $admin = Administrator::where("email", $request->user()->email)->first();
            if (!$admin) {
                return response()->json([
                    "message" => "unathorized"
                ], 401);
            }

            $validate = $request->validate([
                "name" => "string|required",
                "category_id" => "required|integer",
                "description" => ""
            ]);

            $item = Item::create($validate);

            return response()->json([
                "message" => "Item created successfully.",
                "data" => $item
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                "message" => "error",
                "errors" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    // public function show(Request $request, $id)
    // {
    //     try {
    //         $admin = Administrator::where("email", $request->user()->email)->first();
    //         if (!$admin) {
    //             return response()->json([
    //                 "message" => "unathorized"
    //             ], 401);
    //         }
            
    //         // $items = ;

    //         if (!$items) {
    //             return response()->json([
    //                 "message" => "item not found."
    //             ], 404);
    //         }
    //         return response()->json([
    //             "message" => "success",
    //             "data" => $items
    //         ], 200);
    //     } catch (Exception $e) {
    //         return response()->json([
    //             "message" => "error",
    //             "errors" => $e->getMessage()
    //         ], 500);
    //     }
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $admin = Administrator::where("email", $request->user()->email)->first();
            if (!$admin) {
                return response()->json([
                    "message" => "unathorized"
                ], 401);
            }

            $validate = $request->validate([
                "name" => "string|required",
                "category_id" => "required|integer",
                "description" => ""
            ]);

            $item = Item::find($id);
            if (!$item) {
                return response()->json([
                    "message" => "item not found."
                ], 404);
            }

            $item->update($validate);

            return response()->json([
                "message" => "Item updated successfully.",
                "data" => $item
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                "message" => "error",
                "errors" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id, Request $request)
    {
        try {
            $admin = Administrator::where("email", $request->user()->email)->first();
            if (!$admin) {
                return response()->json([
                    "message" => "unathorized"
                ], 401);
            }

            $item = Item::find($id);
            if (!$item) {
                return response()->json([
                    "message" => "item not found."
                ], 404);
            }

            $item->delete();

            return response()->json([
                "message" => "Item deleted successfully.",
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "message" => "error",
                "errors" => $e->getMessage()
            ], 500);
        }
    }
}

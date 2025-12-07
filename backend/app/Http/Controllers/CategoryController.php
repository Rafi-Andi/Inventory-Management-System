<?php

namespace App\Http\Controllers;

use App\Models\Administrator;
use App\Models\Category;
use Exception;
use Illuminate\Http\Request;

use function Symfony\Component\Clock\now;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $category = Category::select('id', 'name', 'created_at')->withCount('items')->get();

            return response()->json([
                "message" => "success",
                "data" => $category
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
                "name" => "string|required"
            ]);

            $category = Category::create([
                "name" => $validate['name']
            ]);

            return response()->json([
                "message" => "Category created successfully.",
                "data" => $category
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
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category) {}

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
                "name" => "string|required"
            ]);

            $category = Category::find($id);
            if (!$category) {
                return response()->json([
                    "message" => "Category not found."
                ], 404);
            }

            $category->update([
                "name" => $validate['name'],
                "updated_at" => now()
            ]);

            return response()->json([
                "message" => "Category updated successfully.",
                "data" => $category
            ], 200);
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

            $category = Category::find($id);
            if (!$category) {
                return response()->json([
                    "message" => "Category not found."
                ], 404);
            }

            $category->delete();

            return response()->json([
                "message" => "Category deleted successfully.",
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "message" => "error",
                "errors" => $e->getMessage()
            ], 500);
        }
    }
}

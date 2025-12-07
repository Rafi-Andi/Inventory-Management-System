<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\WarehouseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function(){
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::apiResource('/categories', CategoryController::class);
    Route::apiResource('/warehouses', WarehouseController::class);
    Route::get('/warehouse-stock', [WarehouseController::class, 'warehouse_stock']);
    Route::apiResource('/items', ItemController::class);
    Route::get('/items/{id}/warehouse', [ItemController::class, 'show']);
    Route::get('/items/{id}/warehouse', [ItemController::class, 'show']);
    Route::apiResource('purchase-orders', PurchaseOrderController::class);
    Route::get('/my-purchase-orders', [PurchaseOrderController::class, 'my_purchase_order']);
    Route::post('purchase-orders/{id}/approve', [PurchaseOrderController::class, 'approve']);
    Route::post('purchase-orders/{id}/reject', [PurchaseOrderController::class, 'reject']);
    Route::get('/suppliers', [SupplierController::class, 'index']);
});
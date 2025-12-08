<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Item;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Models\Administrator;
use App\Models\PurchaseOrder;
use App\Models\WarehouseItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\PurchaseOrderResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PurchaseOrderController extends Controller
{
    public function store(Request $request)
    {
        try {
            $user = $request->user();
            if ($user->role !== 'manager') {
                return response()->json([
                    "message" => "unathorized"
                ], 401);
            }
            $warehouse_id = $user->warehouse_id;
            $validate = $request->validate([
                "supplier_id" => "required",
                "from_warehouse_id" => "required",
                "items" => "required|array",
                "items.*.item_id" => "required",
                "items.*.quantity" => "integer|min:1",
            ]);

            $from_warehouse_id = $validate['from_warehouse_id'];
            $itemsToOrder = $validate['items'];

            foreach ($itemsToOrder as $item) {
                $itemId = $item['item_id'];
                $requestedQuantity = $item['quantity'];
                $warehouseItem = WarehouseItem::where('warehouse_id', $from_warehouse_id)->where('item_id', $item['item_id'])->first();

                if (!$warehouseItem || $warehouseItem->stock < $requestedQuantity) {
                    return response()->json([
                        "message" => "Stock Unavailable.",
                    ], 422);
                }
            }

            $purchase_order = PurchaseOrder::create([
                "user_id" => $user->id,
                "supplier_id" => $validate['supplier_id'],
                "from_warehouse_id" => $validate['from_warehouse_id'],
                "to_warehouse_id" => $warehouse_id,
            ]);

            $purchase_order->purchase_order_items()->attach($validate['items']);

            return response()->json([
                "message" => "Purchase order created successfully.",
                "data" => $purchase_order
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                "message" => "error",
                "errors" => $e->getMessage()
            ], 500);
        }
    }

    public function index(Request $request)
    {
        $user = $request->user();

        $admins = Administrator::where('id', $user->id)->first();

        if (!$admins) {
            return response()->json([
                "message" => "unathorized"
            ], 403);
        }

        $po = PurchaseOrder::with('supplier:id,name', 'from_warehouse:id,name', 'to_warehouse:id,name', 'user:id,name', 'po_items')->get();

        return response()->json([
            "data" => PurchaseOrderResource::collection($po)
        ]);
    }

    public function show(Request $request, $id)
    {
        $user = $request->user();

        $admins = Administrator::where('id', $user->id)->first();

        if (!$admins && $user->role !== 'manager') {
            return response()->json([
                "message" => "unathorized"
            ], 403);
        }

        $po = PurchaseOrder::with('purchase_order_items:id,name,category_id,description', 'user:id,name,role', 'supplier:id,name')->find($id);

        return response()->json([
            "message" => "succcess",
            "purchase_order" => $po
        ], 200);
    }

    public function my_purchase_order(Request $request)
    {
        $user = $request->user();

        if ($user->role !== 'manager') {
            return response()->json([
                "message" => "unathorized"
            ], 403);
        }
        $po = PurchaseOrder::with('user:id,name,email,role', 'supplier:id,name,phone')->where('user_id', $user->id)->get();

        return response()->json([
            "message" => "success",
            "purchase_orders" => $po
        ], 200);
    }

    public function approve(Request $request, $id)
    {
        $user = $request->user();

        if (!$user || !Administrator::where('id', $user->id)->exists()) {
            return response()->json(["message" => "Unauthorized access."], 403);
        }

        try {
            DB::beginTransaction();

            $purchaseOrder = PurchaseOrder::with('purchase_order_items')
                ->lockForUpdate()
                ->findOrFail($id);

            if ($purchaseOrder->status !== 'pending') {
                throw new Exception("Purchase order has already been processed..", 409);
            }

            $toWarehouseId = $purchaseOrder->to_warehouse_id;
            $fromWarehouseId = $purchaseOrder->from_warehouse_id;

            if ($fromWarehouseId) {
                $itemIds = $purchaseOrder->purchase_order_items->pluck('pivot.item_id');

                $warehouseStocks = WarehouseItem::where('warehouse_id', $fromWarehouseId)
                    ->whereIn('item_id', $itemIds)
                    ->pluck('stock', 'item_id')
                    ->toArray();

                foreach ($purchaseOrder->purchase_order_items as $item) {
                    $itemId = $item->pivot->item_id;
                    $requestedQuantity = (int) $item->pivot->quantity;
                    $availableStock = $warehouseStocks[$itemId] ?? 0;

                    if ($requestedQuantity <= 0) {
                        throw new Exception("Quantity for item '{$item->name}' must be greater than zero.", 400);
                    }

                    if ($availableStock < $requestedQuantity) {
                        $itemName = $item->name ?? 'Unknown Item';
                        throw new Exception("Insufficient stock in source warehouse for item: {$itemName}. Available: {$availableStock}, Required: {$requestedQuantity}", 400);
                    }
                }
            }

            foreach ($purchaseOrder->purchase_order_items as $item) {
                $itemId = $item->pivot->item_id;
                $quantity = (int) $item->pivot->quantity;

                if ($quantity <= 0) continue;

                WarehouseItem::updateOrCreate(
                    ['warehouse_id' => $toWarehouseId, 'item_id' => $itemId],
                    ['stock' => DB::raw('stock + ' . $quantity)]
                );

                if ($fromWarehouseId) {
                    WarehouseItem::where('warehouse_id', $fromWarehouseId)
                        ->where('item_id', $itemId)
                        ->update(['stock' => DB::raw('stock - ' . $quantity)]);
                }
            }

            $purchaseOrder->status = 'received';
            $purchaseOrder->save();

            DB::commit();

            return response()->json(["message" => "Purchase order approved successfully. Stock has been updated."], 200);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json(["message" => "Purchase Order not found."], 404);
        } catch (Exception $e) {
            DB::rollBack();
            $statusCode = $e->getCode() ?: 500;
            $message = $e->getMessage();

            if (in_array($statusCode, [409, 400])) {
                return response()->json(["message" => $message], $statusCode);
            }

            return response()->json(["message" => "Error processing purchase order.", "errors" => $message], 500);
        }
    }
    public function reject(Request $request, $id)
    {
        $user = $request->user();

        $admins = Administrator::where('id', $user->id)->first();

        if (!$admins) {
            return response()->json([
                "message" => "unathorized"
            ], 403);
        }

        $po = PurchaseOrder::find($id);

        if (!$po) {
            return response()->json([
                "message" => "not found"
            ], 404);
        }

        if ($po->status !== 'pending') {
            return response()->json([
                "message" => "Purchase order has already been processed."
            ], 409);
        }

        $po->update([
            "status" => "cancelled"
        ]);

        return response()->json([
            "message" => "Purchase order rejected successfully."
        ], 200);
    }
}

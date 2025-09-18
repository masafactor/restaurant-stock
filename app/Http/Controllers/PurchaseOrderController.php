<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderLine;
use App\Http\Requests\StorePurchaseOrderRequest;
use Illuminate\Support\Facades\DB;


class PurchaseOrderController extends Controller
{
    
    public function index()
    {
        $this->authorize('viewAny', PurchaseOrder::class);
        return PurchaseOrder::with('supplier')
            ->orderByDesc('id')->paginate(20);
    }

    public function store(StorePurchaseOrderRequest $request)
    {
        $data = $request->validated();

        return DB::transaction(function () use ($data) {
            $po = PurchaseOrder::create([
                'supplier_id' => $data['supplier_id'],
                'ordered_at'  => $data['ordered_at'] ?? now(),
                'expected_at' => $data['expected_at'] ?? null,
                'note'        => $data['note'] ?? null,
            ]);

            foreach ($data['lines'] ?? [] as $ln) {
                PurchaseOrderLine::create([
                    'purchase_order_id' => $po->id,
                    'item_id'    => $ln['item_id'],
                    'qty'        => $ln['qty'],
                    'unit_cost'  => $ln['unit_cost'],
                    'line_total' => round($ln['qty'] * $ln['unit_cost'], 2),
                ]);
            }
            $po->recalcTotal();
            return response()->json($po->load('lines','supplier'), 201);
        });
    }

    public function show(PurchaseOrder $purchase_order)
    {
        $this->authorize('view', $purchase_order);
        return $purchase_order->load('supplier','lines.item');
    }

    public function update(StorePurchaseOrderRequest $request, PurchaseOrder $purchase_order)
    {
        $data = $request->validated();

        return DB::transaction(function () use ($purchase_order, $data) {
            $purchase_order->update([
                'supplier_id' => $data['supplier_id'],
                'ordered_at'  => $data['ordered_at'] ?? $purchase_order->ordered_at,
                'expected_at' => $data['expected_at'] ?? $purchase_order->expected_at,
                'note'        => $data['note'] ?? $purchase_order->note,
            ]);

            if (isset($data['lines'])) {
                $purchase_order->lines()->delete();
                foreach ($data['lines'] as $ln) {
                    PurchaseOrderLine::create([
                        'purchase_order_id' => $purchase_order->id,
                        'item_id'    => $ln['item_id'],
                        'qty'        => $ln['qty'],
                        'unit_cost'  => $ln['unit_cost'],
                        'line_total' => round($ln['qty'] * $ln['unit_cost'], 2),
                    ]);
                }
            }
            $purchase_order->recalcTotal();
            return $purchase_order->load('lines','supplier');
        });
    }

    public function destroy(PurchaseOrder $purchase_order)
    {
        $this->authorize('delete', $purchase_order);
        $purchase_order->delete();
        return response()->noContent();
    }

    // --- 状態遷移 ---
    public function submit(PurchaseOrder $purchase_order)
    {
        $this->authorize('submit', $purchase_order);
        $purchase_order->update(['status' => PurchaseOrder::STATUS_SUBMITTED]);
        return $purchase_order;
    }

    public function receive(PurchaseOrder $purchase_order)
    {
        $this->authorize('receive', $purchase_order);

        $purchase_order->update([
            'status'      => PurchaseOrder::STATUS_RECEIVED,
            'received_at' => now(),
        ]);
        // 受入による在庫反映は後続の StockMovement 実装で行う（TODO）
        return $purchase_order;
    }

    public function close(PurchaseOrder $purchase_order)
    {
        $this->authorize('close', $purchase_order);
        $purchase_order->update(['status' => PurchaseOrder::STATUS_CLOSED]);
        return $purchase_order;
    }
}

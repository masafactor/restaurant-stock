<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStockMovementRequest;
use App\Models\Item;
use App\Models\Location;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StockMovementController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', StockMovement::class);

        $itemId     = $request->integer('item_id');
        $locationId = $request->integer('location_id');
        $type       = $request->string('type')->toString() ?: null;
        $from       = $request->date('from');
        $to         = $request->date('to');

        $movements = StockMovement::with(['item:id,sku,name', 'location:id,name'])
            ->when($itemId,     fn($q) => $q->where('item_id', $itemId))
            ->when($locationId, fn($q) => $q->where('location_id', $locationId))
            ->when($type,       fn($q) => $q->where('type', $type))
            ->when($from,       fn($q) => $q->whereDate('moved_at', '>=', $from))
            ->when($to,         fn($q) => $q->whereDate('moved_at', '<=', $to))
            ->orderByDesc('moved_at')
            ->orderByDesc('id')
            ->paginate(20)
            ->through(fn(StockMovement $m) => [
                'id'        => $m->id,
                'type'      => $m->type,
                'qty'       => (float)$m->qty,
                'unit_cost' => $m->unit_cost ? (float)$m->unit_cost : null,
                'moved_at'  => $m->moved_at?->format('Y-m-d'),
                'note'      => $m->note,
                'item'      => $m->item?->only('id','sku','name'),
                'location'  => $m->location?->only('id','name'),
                'can'       => [
                    'delete' => $request->user()->can('delete', $m),
                ],
            ]);

        // フィルター用マスタ
        $items     = Item::orderBy('name')->get(['id','sku','name']);
        $locations = Location::where('is_active', true)->orderBy('name')->get(['id','name']);

        // 合計（絞り込み条件に一致した範囲）
        $sum = StockMovement::when($itemId,     fn($q) => $q->where('item_id', $itemId))
            ->when($locationId, fn($q) => $q->where('location_id', $locationId))
            ->when($type,       fn($q) => $q->where('type', $type))
            ->when($from,       fn($q) => $q->whereDate('moved_at', '>=', $from))
            ->when($to,         fn($q) => $q->whereDate('moved_at', '<=', $to))
            ->selectRaw("
                SUM(
                  CASE type
                    WHEN 'receive' THEN qty
                    WHEN 'waste'   THEN -qty
                    WHEN 'adjust'  THEN qty
                  END
                ) as total_qty
            ")->value('total_qty') ?? 0;

        return Inertia::render('StockMovements/Index', [
            'movements' => $movements,
            'items'     => $items,
            'locations' => $locations,
            'filters'   => [
                'item_id'     => $itemId,
                'location_id' => $locationId,
                'type'        => $type,
                'from'        => $from?->format('Y-m-d'),
                'to'          => $to?->format('Y-m-d'),
            ],
            'sum'       => (float)$sum,
            'can'       => [
                'create' => $request->user()->can('create', StockMovement::class),
            ],
        ]);
    }

    public function create()
    {
        $this->authorize('create', StockMovement::class);

        return Inertia::render('StockMovements/Create', [
            'items'     => Item::orderBy('name')->get(['id','sku','name']),
            'locations' => Location::where('is_active', true)->orderBy('name')->get(['id','name']),
        ]);
    }

    public function store(StoreStockMovementRequest $request)
    {
        $data = $request->validated();
        StockMovement::create($data);

        return redirect()
            ->route('stock-movements.index')
            ->with('success', '在庫異動を登録しました');
    }

    // app/Http/Controllers/StockMovementController.php
    public function show(StockMovement $stockMovement)
    {
        $this->authorize('view', $stockMovement);

        return Inertia::render('StockMovements/Show', [
            'movement' => $stockMovement->load('item', 'location'),
        ]);
    }

    public function edit(StockMovement $stockMovement)
    {
        $this->authorize('update', $stockMovement);

        return Inertia::render('StockMovements/Edit', [
            'movement'   => $stockMovement,
            'items'      => Item::select('id','name')->get(),
            'locations'  => Location::select('id','name')->get(),
        ]);
    }

    public function update(StoreStockMovementRequest $request, StockMovement $stockMovement)
    {
        $stockMovement->update($request->validated());

        return redirect()->route('stock-movements.index')
            ->with('success', '在庫異動を更新しました');
    }


    public function destroy(StockMovement $stockMovement)
    {
        $this->authorize('delete', $stockMovement);

        $stockMovement->delete();

        return redirect()
            ->route('stock-movements.index')
            ->with('success', '在庫異動を削除しました');
    }
}

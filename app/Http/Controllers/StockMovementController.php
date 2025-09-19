<?php

namespace App\Http\Controllers;

use App\Models\StockMovement;
use App\Http\Requests\StoreStockMovementRequest;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class StockMovementController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $this->authorize('viewAny', StockMovement::class);

        $q = StockMovement::query()
            ->with('item')
            ->when($request->integer('item_id'), fn($q,$id) => $q->where('item_id',$id))
            ->when($request->date('from'), fn($q,$d) => $q->whereDate('moved_at','>=',$d))
            ->when($request->date('to'), fn($q,$d) => $q->whereDate('moved_at','<=',$d))
            ->orderByDesc('id');

        return $q->paginate(20);
    }

    public function store(StoreStockMovementRequest $request)
    {
        $movement = StockMovement::create($request->validated());
        return response()->json($movement, 201);
    }

    public function show(StockMovement $stock_movement)
    {
        $this->authorize('view', $stock_movement);
        return $stock_movement->load('item');
    }

    public function update(StoreStockMovementRequest $request, StockMovement $stock_movement)
    {
        $this->authorize('update', $stock_movement);
        $stock_movement->update($request->validated());
        return $stock_movement;
    }

    public function destroy(StockMovement $stock_movement)
    {
        $this->authorize('delete', $stock_movement);
        $stock_movement->delete();
        return response()->noContent();
    }
}

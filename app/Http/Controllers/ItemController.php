<?php

namespace App\Http\Controllers;


use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use App\Models\Item;
use Illuminate\Http\Request;


class ItemController extends Controller
{
    

    public function index(Request $request) {
        $this->authorize('viewAny', Item::class);
        $q = Item::query()
            ->when($request->string('keyword'), fn($qq,$kw)=>$qq->where(fn($w)=>$w
                ->where('sku','like',"%$kw%")->orWhere('name','like',"%$kw%")
            ))
            ->orderByDesc('id')
            ->paginate(20);
        return response()->json($q);
    }

    public function store(StoreItemRequest $request) {
        return response()->json(Item::create($request->validated()), 201);
    }

    public function show(Item $item) {
        $this->authorize('view', $item);
        return response()->json($item);
    }

    public function update(UpdateItemRequest $request, Item $item) {
        $item->update($request->validated());
        return response()->json($item);
    }

    public function destroy(Item $item) {
        $this->authorize('delete', $item);
        $item->delete();
        return response()->noContent();
    }
}


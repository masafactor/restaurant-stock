<?php

namespace App\Http\Controllers;


use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ItemController extends Controller
{
    

    public function index(Request $request)
    {
        $this->authorize('viewAny', Item::class);

        $kw = $request->string('keyword')->trim();

        $items = Item::with('category')
            ->when($kw->isNotEmpty(), function ($q) use ($kw) {
                $q->where(function ($w) use ($kw) {
                    $w->where('sku', 'like', "%{$kw}%")
                    ->orWhere('name', 'like', "%{$kw}%");
                });
            })
            ->orderByDesc('id')
            ->paginate(20)
            // ← ここで各行の表示用データに整形しつつ権限を付与
            ->through(function (Item $item) use ($request) {
                return [
                    'id'            => $item->id,
                    'sku'           => $item->sku,
                    'name'          => $item->name,
                    'standard_cost' => $item->standard_cost,
                    'category'      => [
                        'name' => optional($item->category)->name,
                    ],
                    'can' => [
                        'update' => $request->user()->can('update', $item),
                        'delete' => $request->user()->can('delete', $item),
                    ],
                ];
            });

        return Inertia::render('Items/Index', [
            'items'   => $items,
            'filters' => ['keyword' => (string) $kw],
            'can'     => [
                'create' => $request->user()->can('create', Item::class),
            ],
        ]);
    }


        public function create()
    {
        $this->authorize('create', Item::class);

        return Inertia::render('Items/Create', [
            'categories' => Category::select('id','name')->orderBy('name')->get(),
            'units'      => ['pcs','kg','g','L','mL'], // 必要なら
        ]);
    }

    public function edit(Item $item)
    {

        $this->authorize('update', $item);

        return Inertia::render('Items/Edit', [
            'item'       => $item->only('id','sku','name','unit','standard_cost','is_active','category_id'),
            'categories' => Category::select('id','name')->orderBy('name')->get(),
            'units'      => ['pcs','kg','g','L','mL'],
        ]);
    }
    
    public function store(StoreItemRequest $request)
    {
        $this->authorize('create', Item::class);

        Item::create($request->validated());

        return redirect()->route('items.index')->with('success', '商品を登録しました');
    }

    public function show(Item $item)
    {
        $this->authorize('view', $item);

        $item->load('category');

        return Inertia::render('Items/Show', [
            'item' => [
                'id'            => $item->id,
                'sku'           => $item->sku,
                'name'          => $item->name,
                'standard_cost' => $item->standard_cost,
                'unit'          => $item->unit,
                'is_active'     => $item->is_active,
                'category'      => $item->category?->only('id','name'),
                'can' => [
                    'update' => request()->user()->can('update', $item),
                    'delete' => request()->user()->can('delete', $item),
                ],
            ],
        ]);
    }


    public function update(UpdateItemRequest $request, Item $item)
    {
        $this->authorize('update', $item);

        $item->update($request->validated());

        return redirect()->route('items.index')
            ->with('success', '商品を更新しました');
    }


    public function destroy(Item $item)
    {
        $this->authorize('delete', $item);

        $item->delete();

        return redirect()->route('items.index')
            ->with('success', '商品を削除しました');
    }

}


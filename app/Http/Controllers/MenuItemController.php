<?php

namespace App\Http\Controllers;

use App\Models\{MenuItem, RecipeIngredient};
use App\Http\Requests\StoreMenuItemRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuItemController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $this->authorize('viewAny', MenuItem::class);

        $q = MenuItem::query()
            ->with(['category','ingredients.item'])
            ->when($request->boolean('active_only'), fn($q)=>$q->where('is_active', true))
            ->orderBy('id');

        return $q->paginate(20);
    }

    public function store(StoreMenuItemRequest $request)
    {
        $this->authorize('create', MenuItem::class);

        return DB::transaction(function () use ($request) {
            $menu = MenuItem::create($request->safe()->only(['name','category_id','price','is_active']));

            if ($ings = $request->input('ingredients')) {
                foreach ($ings as $row) {
                    $menu->ingredients()->create($row);
                }
                $menu->recalcCost();
            }

            return response()->json($menu->load('ingredients.item'), 201);
        });
    }

    public function show(MenuItem $menu_item)
    {
        $this->authorize('view', $menu_item);
        return $menu_item->load(['category','ingredients.item']);
    }

    public function update(StoreMenuItemRequest $request, MenuItem $menu_item)
    {
        $this->authorize('update', $menu_item);

        return DB::transaction(function () use ($request, $menu_item) {
            $menu_item->update($request->safe()->only(['name','category_id','price','is_active']));

            if ($request->has('ingredients')) {
                // 全入れ替え（シンプル運用）
                $menu_item->ingredients()->delete();
                foreach ((array) $request->input('ingredients') as $row) {
                    $menu_item->ingredients()->create($row);
                }
            }

            $menu_item->recalcCost();
            return $menu_item->load('ingredients.item');
        });
    }

    public function destroy(MenuItem $menu_item)
    {
        $this->authorize('delete', $menu_item);
        $menu_item->delete();
        return response()->noContent();
    }
}

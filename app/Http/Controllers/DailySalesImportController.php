<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportDailySalesRequest;
use App\Models\DailySale;
use App\Models\MenuItem;
use App\Models\StockMovement;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DailySalesImportController extends Controller
{
    use AuthorizesRequests;

    public function __invoke(ImportDailySalesRequest $request)
    {
        $soldAt = $request->date('sold_at');
        $source = $request->input('source');
        $path   = $request->file('file')->getRealPath();

        $rows = [];
        if (($h = fopen($path, 'r')) !== false) {
            while (($data = fgetcsv($h)) !== false) {
                // 期待フォーマット: [menu_item_id, qty_sold] または [menu_item_name, qty_sold]
                // まずは ID 前提で実装（名前対応は後で拡張）
                [$menuItemId, $qty] = [$data[0] ?? null, $data[1] ?? null];
                if (!is_numeric($menuItemId) || !is_numeric($qty)) continue;
                $rows[] = ['menu_item_id'=>(int)$menuItemId, 'qty_sold'=>(float)$qty];
            }
            fclose($h);
        }

        DB::transaction(function () use ($rows, $soldAt, $source) {
            foreach ($rows as $r) {
                // upsert（同一日×メニューは加算）
                $sale = DailySale::firstOrNew([
                    'sold_at' => $soldAt,
                    'menu_item_id' => $r['menu_item_id'],
                ]);
                $sale->qty_sold = ($sale->qty_sold ?: 0) + $r['qty_sold'];
                $sale->source = $sale->source ?: $source;
                $sale->save();

                // 理論在庫減算：メニューのレシピ行を展開して consume を積む
                $menu = MenuItem::with('ingredients.item:id,standard_cost')->find($r['menu_item_id']);
                foreach ($menu->ingredients as $ing) {
                    $waste = $ing->wastage_rate ? (1 - ($ing->wastage_rate / 100)) : 1;
                    $need  = ($ing->qty / max($waste, 0.000001)) * $r['qty_sold']; // 販売数×必要量
                    StockMovement::create([
                        'item_id'   => $ing->item_id,
                        'type'      => 'consume',
                        'qty'       => $need,
                        'unit_cost' => $ing->item->standard_cost,
                        'moved_at'  => $soldAt,
                        'note'      => "Daily sales consume for menu #{$menu->id}",
                    ]);
                }
            }
        });

        return response()->json(['imported' => count($rows)], 201);
    }
}
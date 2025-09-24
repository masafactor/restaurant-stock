<?php
// app/Http/Controllers/DashboardController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function summary(Request $req)
    {
        // ダッシュボードは Manager/Admin に限定（既存の Gate）
        $this->authorize('viewReports');

        // 基本カウント
        $itemsCount     = DB::table('items')->count();
        $suppliersCount = DB::table('suppliers')->count();
        $openPOs        = DB::table('purchase_orders')->whereNot('status', 'completed')->count();

        // 売上（直近7日）
        $sales7 = DB::table('daily_sales as ds')
            ->join('menu_items as mi', 'mi.id', '=', 'ds.menu_item_id')
            ->selectRaw('DATE(ds.sold_at) as date, SUM(ds.qty_sold * COALESCE(mi.price,0)) as revenue')
            ->where('ds.sold_at', '>=', now()->subDays(6)->toDateString())
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // 在庫数量（受入-消費）と評価額
        $onhand = DB::table('stock_movements')
            ->selectRaw('item_id, SUM(CASE WHEN type="receive" THEN qty WHEN type="consume" THEN -qty ELSE 0 END) AS qty')
            ->groupBy('item_id');

        $inv = DB::table('items as i')
            ->leftJoinSub($onhand, 'q', 'q.item_id', '=', 'i.id')
            ->selectRaw('i.id, i.name, COALESCE(q.qty,0) as on_hand, i.standard_cost, COALESCE(q.qty,0) * COALESCE(i.standard_cost,0) as value')
            ->get();

        $inventoryValue = (float) $inv->sum('value');
        $lowStock = $inv->filter(fn($r) => $r->on_hand < 1)->take(10)->values(); // 適当な閾値(ここでは 0)

        return response()->json([
            'cards' => [
                'items'        => $itemsCount,
                'suppliers'    => $suppliersCount,
                'open_pos'     => $openPOs,
                'inventory_value' => $inventoryValue,
            ],
            'sales_last_7_days' => $sales7,     // [{date, revenue}]
            'low_stock_items'   => $lowStock,   // [{id, name, on_hand, standard_cost, value}]
        ]);
    }
}

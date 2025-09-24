<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    // ?from=YYYY-MM-DD&to=YYYY-MM-DD
    public function salesSummary(Request $req)
    {
        $this->authorize('viewReports');

        $from = $req->date('from');
        $to   = $req->date('to');

        // 金額 = qty_sold * menu_items.price（priceがNULLなら0扱い）
        $row = DB::table('daily_sales as ds')
            ->join('menu_items as mi', 'mi.id', '=', 'ds.menu_item_id')
            ->selectRaw('
                SUM(ds.qty_sold * COALESCE(mi.price,0)) AS revenue,
                COUNT(DISTINCT ds.sold_at) AS days,
                SUM(ds.qty_sold) AS total_qty
            ')
            ->when($from && $to, fn($q) => $q->whereBetween('ds.sold_at', [$from, $to]))
            ->first();

        $revenue = (float)($row->revenue ?? 0);
        $days    = (int)($row->days ?? 0);

        return response()->json([
            'from'       => optional($from)->toDateString(),
            'to'         => optional($to)->toDateString(),
            'revenue'    => $revenue,
            'days'       => $days,
            'avg_per_day'=> $days ? $revenue / $days : 0,
            'total_qty'  => (float)($row->total_qty ?? 0),
        ]);
    }

    // 仕入(=購入) vs 売上 を日別で比較
public function purchaseVsSales(Request $req)
{
    $this->authorize('viewReports');

    $from = $req->date('from');
    $to   = $req->date('to');

    // 売上（日別）
    $sales = DB::table('daily_sales as ds')
        ->join('menu_items as mi', 'mi.id', '=', 'ds.menu_item_id')
        ->selectRaw('ds.sold_at as date, SUM(ds.qty_sold * COALESCE(mi.price,0)) AS sales')
        ->when($from && $to, fn($q) => $q->whereBetween('ds.sold_at', [$from, $to]))
        ->groupBy('ds.sold_at');

    // 仕入（日別）
    $purchases = DB::table('purchase_orders as po')
        ->join('purchase_order_lines as pol', 'pol.purchase_order_id', '=', 'po.id')
        ->selectRaw('po.ordered_at as date, SUM(pol.qty * pol.unit_cost) AS purchases')
        ->whereNotNull('po.ordered_at')
        ->when($from && $to, fn($q) => $q->whereBetween('po.ordered_at', [$from, $to]))
        ->groupBy('po.ordered_at');

    // 売上基準 + 仕入のみの日を union（重複防止に whereNull('s.date')）
    $rows1 = DB::query()
        ->fromSub($sales, 's')
        ->leftJoinSub($purchases, 'p', 'p.date', '=', 's.date')
        ->selectRaw('s.date, s.sales, COALESCE(p.purchases,0) AS purchases');

    $rows2 = DB::query()
        ->fromSub($purchases, 'p')
        ->leftJoinSub($sales, 's', 's.date', '=', 'p.date')
        ->whereNull('s.date') // ← ココが重要！
        ->selectRaw('p.date, COALESCE(s.sales,0) AS sales, p.purchases');

    $rows = $rows1->unionAll($rows2)->orderBy('date')->get();

    // もう重複は無いので、そのまま返してOK（保険で groupBy してもOK）
    $data = $rows->map(fn($r) => [
        'date'       => $r->date,
        'sales'      => (float) $r->sales,
        'purchases'  => (float) $r->purchases,
    ])->values();

    return response()->json([
        'from' => $from?->toDateString(),
        'to'   => $to?->toDateString(),
        'data' => $data,
    ]);
}

    // 在庫評価額 = (受入 - 消費) * 標準原価
    public function inventoryValuation()
    {
        $this->authorize('viewReports');

        $mov = DB::table('stock_movements')
            ->selectRaw("
                item_id,
                SUM(CASE WHEN type='receive' THEN qty ELSE 0 END) AS received_qty,
                SUM(CASE WHEN type='consume' THEN qty ELSE 0 END) AS consumed_qty
            ")
            ->groupBy('item_id');

        $rows = DB::table('items as i')
            ->leftJoinSub($mov, 'm', 'm.item_id', '=', 'i.id')
            ->selectRaw("
                i.id, i.sku, i.name, i.standard_cost,
                COALESCE(m.received_qty,0) - COALESCE(m.consumed_qty,0) AS qty,
                (COALESCE(m.received_qty,0) - COALESCE(m.consumed_qty,0)) * i.standard_cost AS valuation
            ")
            ->orderByDesc('valuation')
            ->get();

        return response()->json([
            'total_valuation' => (float)$rows->sum('valuation'),
            'items' => $rows,
        ]);
    }
}

<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SupplierController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::resource('items', ItemController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('purchase-orders', PurchaseOrderController::class);

    // 状態遷移
    Route::post('purchase-orders/{purchase_order}/submit',  [PurchaseOrderController::class, 'submit']);
    Route::post('purchase-orders/{purchase_order}/receive', [PurchaseOrderController::class, 'receive']);
    Route::post('purchase-orders/{purchase_order}/close',   [PurchaseOrderController::class, 'close']);

    Route::resource('stock-movements', \App\Http\Controllers\StockMovementController::class);
    Route::resource('menu-items', \App\Http\Controllers\MenuItemController::class);

    // 売上取込
    Route::post('daily-sales/import', \App\Http\Controllers\DailySalesImportController::class)
        ->name('daily-sales.import');

    // レポート
    Route::get('reports/sales-summary',      [ReportController::class, 'salesSummary'])->name('reports.salesSummary');
    Route::get('reports/purchase-vs-sales',  [ReportController::class, 'purchaseVsSales'])->name('reports.purchaseVsSales');
    Route::get('reports/inventory-valuation',[ReportController::class, 'inventoryValuation'])->name('reports.inventoryValuation');

    // ダッシュボード API
    Route::get('dashboard/summary', [DashboardController::class, 'summary'])->name('dashboard.summary');
});

require __DIR__.'/auth.php';

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('purchase_order_lines', function (Blueprint $table) {
        $table->id();
        $table->foreignId('purchase_order_id')->constrained()->cascadeOnDelete();
        $table->foreignId('item_id')->constrained();             // 既存 items を参照
        $table->decimal('qty', 12, 3);
        $table->decimal('unit_cost', 12, 4);
        $table->decimal('line_total', 12, 2);
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_order_lines');
    }
};

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
        Schema::create('daily_sales', function (Blueprint $t) {
        $t->id();
        $t->date('sold_at');                 // 売上日
        $t->foreignId('menu_item_id')->constrained()->cascadeOnDelete();
        $t->decimal('qty_sold', 12, 3);      // 販売数
        $t->string('source')->nullable();    // ファイル名等
        $t->timestamps();
        $t->unique(['sold_at','menu_item_id']); // 同日同メニューは1行に集約
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_sales');
    }
};

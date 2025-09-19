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
        Schema::create('stock_movements', function (Blueprint $table) {
        $table->id();

        $table->foreignId('item_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();

        // 受入・廃棄・棚卸調整
        $table->enum('type', ['receive', 'waste', 'adjust']);

        $table->decimal('qty', 12, 3);
        $table->decimal('unit_cost', 12, 4)->nullable(); // 受入等で単価を持たせたい時用
        $table->date('moved_at')->nullable();
        $table->text('note')->nullable();

        $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};

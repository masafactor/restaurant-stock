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
        Schema::create('purchase_orders', function (Blueprint $table) {
        $table->id();
        $table->foreignId('supplier_id')->constrained()->cascadeOnUpdate();
        $table->enum('status', ['draft','submitted','received','closed'])->default('draft');
        $table->date('ordered_at')->nullable();
        $table->date('expected_at')->nullable();
        $table->date('received_at')->nullable();
        $table->decimal('total', 12, 2)->default(0);
        $table->text('note')->nullable();
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};

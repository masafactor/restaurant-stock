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
        Schema::create('menu_items', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
        $table->decimal('price', 12, 2)->nullable();     // 販売価格（任意）
        $table->decimal('cost', 12, 4)->nullable();      // レシピ原価（キャッシュ）
        $table->boolean('is_active')->default(true);
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};

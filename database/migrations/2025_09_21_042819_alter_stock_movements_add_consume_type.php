<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
         DB::statement("
        ALTER TABLE stock_movements
        MODIFY type ENUM('receive','waste','adjust','consume')
        NOT NULL
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("
        ALTER TABLE stock_movements
        MODIFY type ENUM('receive','waste','adjust')
        NOT NULL
        ");
    }
};

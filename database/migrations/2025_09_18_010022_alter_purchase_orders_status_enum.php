<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // ① 一時的に両方を許容（closed / completed）
        DB::statement("
            ALTER TABLE purchase_orders
            MODIFY status ENUM('draft','submitted','received','closed','completed')
            NOT NULL DEFAULT 'draft'
        ");

        // ② 旧値を新値へ移行
        DB::statement("
            UPDATE purchase_orders
            SET status = 'completed'
            WHERE status = 'closed'
        ");

        // ③ closed を削って確定
        DB::statement("
            ALTER TABLE purchase_orders
            MODIFY status ENUM('draft','submitted','received','completed')
            NOT NULL DEFAULT 'draft'
        ");
    }

    public function down(): void
    {
        // 逆手順：両方許容 → completed を closed に戻す → completed を削除
        DB::statement("
            ALTER TABLE purchase_orders
            MODIFY status ENUM('draft','submitted','received','closed','completed')
            NOT NULL DEFAULT 'draft'
        ");

        DB::statement("
            UPDATE purchase_orders
            SET status = 'closed'
            WHERE status = 'completed'
        ");

        DB::statement("
            ALTER TABLE purchase_orders
            MODIFY status ENUM('draft','submitted','received','closed')
            NOT NULL DEFAULT 'draft'
        ");
    }
};

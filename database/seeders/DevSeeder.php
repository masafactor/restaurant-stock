<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Schema;
use App\Models\{Item, Category, Supplier, User};
use Illuminate\Database\Seeder;

class DevSeeder extends Seeder
{
    public function run(): void
    {
        // 1) 依存関係を考慮して安全に空にする
        Schema::disableForeignKeyConstraints();
        Item::truncate();
        Category::truncate();
        Supplier::truncate();
        Schema::enableForeignKeyConstraints();

        // 2) 必要データ作成
        User::updateOrCreate(['email'=>'admin@example.com'],   ['name'=>'Admin',   'role'=>'Admin',   'password'=>bcrypt('password')]);
        User::updateOrCreate(['email'=>'manager@example.com'], ['name'=>'Manager', 'role'=>'Manager', 'password'=>bcrypt('password')]);
        User::updateOrCreate(['email'=>'staff@example.com'],   ['name'=>'Staff',   'role'=>'Staff',   'password'=>bcrypt('password')]);

        $cats = Category::factory()->count(3)->create();
        Supplier::factory()->count(2)->create();

        // 3) SKU を連番で作り直し、カテゴリーをランダムに付与
        for ($i = 1; $i <= 25; $i++) {
            Item::create([
                'sku'         => sprintf('SKU-%03d', $i),
                'name'        => fake()->words(2, true),
                'unit'        => fake()->randomElement(['pcs','kg','l']),
                'standard_cost'=> fake()->randomFloat(2, 10, 100),
                'is_active'   => true,
                'category_id' => $cats->random()->id,
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Layer;
use App\Models\Layup;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            ['name' => 'Test User', 'password' => bcrypt('password')]
        );

        Supplier::factory(5)->create()->each(function (Supplier $supplier) {
            Layup::factory(3)->create(['supplier_id' => $supplier->id])->each(function (Layup $layup) {
                $orders = range(1, 5);
                foreach ($orders as $order) {
                    Layer::factory()->create([
                        'layup_id'    => $layup->id,
                        'layer_order' => $order,
                    ]);
                }
            });
        });
    }
}


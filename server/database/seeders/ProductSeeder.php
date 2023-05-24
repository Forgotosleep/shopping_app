<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::factory()->create([
            'merchant_id' => 1,
            'name' => 'Ikan Kembung',
            'description' => 'Ikan yang terlalu haus',
            'price' => 25000,
            'status' => 'active',
        ]);
        Product::factory()->create([
            'merchant_id' => 1,
            'name' => 'Ikan Salmon',
            'description' => 'Yoggy Bear\'s favorite fish',
            'price' => 60000,
            'status' => 'active',
        ]);
        Product::factory()->create([
            'merchant_id' => 1,
            'name' => 'Kepiting Kenari',
            'description' => 'Scientists recommends against choking while eating this particular crustacean.',
            'price' => 20000,
            'status' => 'active',
        ]);
    }
}

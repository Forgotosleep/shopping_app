<?php

namespace Database\Seeders;

use App\Models\Merchant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MerchantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Merchant::factory()->create([
            'user_id' => 1,
            'name' => 'Willem Fisheries',
            'description' => 'The freshest fish',
            'balance' => 100000,
            'slug' => 'willem-fisheries',
            'status' => 'active'
        ]);
    }
}

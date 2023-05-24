<?php

namespace Database\Seeders;

use App\Models\Payment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Payment::factory()->create([
            'name' => 'Cash', 
            'value' => 'cash',
            'status' => true,
        ]);
        Payment::factory()->create([
            'name' => 'OVO', 
            'value' => 'ovo',
            'status' => true,
        ]);
        Payment::factory()->create([
            'name' => 'Credit Card', 
            'value' => 'cc',
            'status' => true,
        ]);
    }
}

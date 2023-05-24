<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@mail.com',
            'phone' => '8111111',
            'password' => 'admin',
            'otp' => 11111
        ]);
        User::factory()->create([
            'name' => 'Willem',
            'email' => 'willem@test.com',
            'phone' => '81234567',
            'password' => 'password',
            'otp' => 12345
        ]);

        User::factory()->create([
            'name' => 'Brian',
            'email' => 'brian@test.com',
            'phone' => '87654321',
            'password' => 'password',
            'otp' => 54321
        ]);
    }
}

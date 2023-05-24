<?php

namespace Database\Factories;
use App\Models\Merchant;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'merchant_id' => fake()->numberBetween(1, Merchant::all()->count()),
            'name' => fake()->words(2),
            'description' => fake()->sentence(2),
            'price' => fake()->numberBetween(10000, 100000),
            'status' => 'active',
        ];
    }
}

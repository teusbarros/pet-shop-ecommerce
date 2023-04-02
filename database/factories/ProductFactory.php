<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
            'category_uuid' => Category::all()->random()->uuid,
            'uuid' => fake()->uuid(),
            'title' => fake()->text(50),
            'price' => fake()->randomFloat(2, 1, 500),
            'description' => fake()->text(),
            'metadata' => json_encode([
                'brand' => Brand::all()->random()->uuid,
                'image' => Str::uuid()
            ]),
            'created_at' => fake()->date,
            'updated_at' => fake()->date,
        ];
    }
}

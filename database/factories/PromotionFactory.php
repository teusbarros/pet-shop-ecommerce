<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Promotion>
 */
class PromotionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => fake()->uuid(),
            'title' => fake()->text(50),
            'content' => fake()->paragraph(),
            'metadata' => json_encode([
                'valid_from' => fake()->date,
                'valid_to' =>fake()->date,
                'image' => Str::uuid()
            ]),
            'created_at' => fake()->date,
            'updated_at' => fake()->date,
        ];
    }
}

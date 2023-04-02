<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class PostFactory extends Factory
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
            'slug' => str_replace(' ', '-', fake()->text(50)),
            'content' => implode('', fake()->paragraphs(5)),
            'metadata' => json_encode(['author' => fake()->name, 'image' => Str::uuid()]),
            'created_at' => fake()->date,
            'updated_at' => fake()->date,
        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
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
            'title' => fake()->sentence,
            'content' =>  fake()->sentence,
            'category' =>  fake()->sentence,
            'author' =>  fake()->name,
            'date' => now(),
            'author' =>  fake()->name,
            'image' => fake()->imageUrl(640, 480, 'blog', true)
        ];
    }
}

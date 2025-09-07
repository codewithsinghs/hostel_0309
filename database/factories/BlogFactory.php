<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Blog>
 */
class BlogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // return [
            // 'title' => $this->faker->sentence,
            // 'content' =>  $this->faker->paragraph(),
            // 'author' =>  $this->faker->name(),
            // 'date' => now(),
            // 'author' =>  $this->faker->name(),
            // 'image' => $this->faker->imageUrl(640, 480, 'blog', true)

            'title' => fake()->sentence,
            'content' =>  fake()->sentence,
            'author' =>  fake()->name,
            'date' => now(),
            'author' =>  fake()->name,
            'image' => fake()->imageUrl(640, 480, 'blog', true)

        ];
    }
}

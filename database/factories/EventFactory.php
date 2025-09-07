<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Blog>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = \App\Models\Event::class;

    public function definition(): array
    {
        return [
           'title' => $this->faker->sentence(),
            'content' => $this->faker->sentence(),
            'category' => $this->faker->word(),
            'author' => $this->faker->name(),
            'date' => $this->faker->date(),
            'status' => $this->faker->boolean(),
            'image' => $this->faker->boolean(70) // 70% chance to have an image
                ? UploadedFile::fake()->image('blog.jpg', 640, 480)
                : null,
        ];
    }
}

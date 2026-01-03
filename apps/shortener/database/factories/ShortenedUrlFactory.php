<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ShortenedUrl>
 */
class ShortenedUrlFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'url' => fake()->url(),
            'shortcode' => fake()->randomNumber(6),
            'user_id' => UserFactory::new()->create()->id,
            'title' => fake()->sentence(),
        ];
    }
}

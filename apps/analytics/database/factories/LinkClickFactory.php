<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LinkClick>
 */
class LinkClickFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'shortcode' => $this->faker->lexify('??????'),
            'original_url' => $this->faker->url(),
            'ip_address' => $this->faker->ipv4(),
            'user_agent' => $this->faker->userAgent(),
            'referrer_domain' => $this->faker->domainName(),
            'referrer_url' => $this->faker->url(),
            'is_bot' => $this->faker->boolean(10), // 10% chance of being a bot
            'is_mobile' => $this->faker->boolean(60), // 60% chance of being a mobile device
            'clicked_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
        ];
    }
}

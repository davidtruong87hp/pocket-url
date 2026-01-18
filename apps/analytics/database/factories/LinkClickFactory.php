<?php

namespace Database\Factories;

use App\Helpers\LocationHelper;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

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
        $location = LocationHelper::random();

        return [
            'shortcode' => $this->faker->lexify('??????'),
            'ip_address' => $this->faker->ipv4(),
            'user_agent' => $this->faker->userAgent(),
            'referrer_domain' => $this->faker->domainName(),
            'referrer_url' => $this->faker->url(),
            'is_bot' => $this->faker->boolean(10), // 10% chance of being a bot
            'is_mobile' => $this->faker->boolean(60), // 60% chance of being a mobile device
            'clicked_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'country' => $location['country_code'],
            'country_name' => $location['country'],
            'city' => $location['city'],
            'region' => $location['region'],
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
            'device_type' => $this->faker->randomElement(['desktop', 'tablet', 'mobile']),
            'os_name' => $this->faker->randomElement(['Windows', 'Mac', 'Linux']),
            'os_version' => $this->faker->randomElement(['10', '11', '12']),
            'browser_name' => $this->faker->randomElement(['Chrome', 'Firefox', 'Safari']),
            'browser_version' => $this->faker->randomElement(['90', '91', '92']),
        ];
    }

    /**
     * Create click from specific country
     */
    public function fromCountry(string $countryCode): static
    {
        if (empty($countryCode)) {
            return $this;
        }

        return $this->state(function (array $attributes) use ($countryCode) {
            $location = LocationHelper::randomFor($countryCode);

            return [
                'country' => $location['country_code'],
                'country_name' => $location['country'],
                'region' => $location['region'],
                'city' => $location['city'],
            ];
        });
    }

    /**
     * Create click from specific region
     */
    public function fromRegion(string $countryCode, string $regionName): static
    {
        if (empty($countryCode) || empty($regionName)) {
            return $this;
        }

        return $this->state(function (array $attributes) use ($countryCode, $regionName) {
            $location = LocationHelper::randomFromRegion($countryCode, $regionName);

            return [
                'country' => $location['country'],
                'country_code' => $location['country_code'],
                'region' => $location['region'],
                'city' => $location['city'],
            ];
        });
    }

    public function onDate($date): static
    {
        return $this->state(function (array $attributes) use ($date) {
            $startOfDay = Carbon::parse($date)->startOfDay();
            $endOfDay = Carbon::parse($date)->endOfDay();

            return [
                'clicked_at' => $this->faker->dateTimeBetween($startOfDay, $endOfDay),
            ];
        });
    }

    public function forShortCode(string $code): static
    {
        return $this->state(function (array $attributes) use ($code) {
            return [
                'shortcode' => $code,
            ];
        });
    }
}

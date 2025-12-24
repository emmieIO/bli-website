<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid'=> Str::uuid(),
            'title' => $theme = $this->faker->sentence(10),
            'slug' => Str::slug($theme),
            'description' => $this->faker->paragraph,
            'program_cover' => $this->faker->imageUrl(640, 480, 'church', true, 'cover'), // optional
            'mode' => $this->faker->randomElement(['online', 'offline', 'hybrid']),
            'start_date' => $this->faker->dateTimeBetween('now', '+1 week'),
            'end_date' => $this->faker->optional()->dateTimeBetween('+1 week', '+1 month'),
            "location" => $this->faker->url(),
            'metadata' => [
                'organizer' => $this->faker->company,
                'sponsor' => $this->faker->company,
                'contact_email' => $this->faker->safeEmail,
                'tags' => $this->faker->words(3),
                'language' => $this->faker->languageCode,
            ],
        ];
    }
}

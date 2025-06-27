<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Programme>
 */
class ProgrammeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'theme' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph,
            'program_cover' => $this->faker->imageUrl(640, 480, 'church', true, 'cover'), // optional
            'mode' => $this->faker->randomElement(['online', 'onsite', 'hybrid']),
            'start_date' => $this->faker->dateTimeBetween('now', '+1 week'),
            'end_date' => $this->faker->optional()->dateTimeBetween('+1 week', '+1 month'),
            'host' => $this->faker->name,
            'ministers' => json_encode([
                ['name' => $this->faker->name, 'title' => $this->faker->jobTitle],
                ['name' => $this->faker->name, 'title' => $this->faker->jobTitle],
            ]),
        ];
    }
}

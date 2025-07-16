<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Speaker>
 */
class SpeakerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'title' => $this->faker->jobTitle(),
            'organization' => $this->faker->company(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->optional()->phoneNumber(),
            'bio' => $this->faker->paragraphs(2, true),
            'photo' =>  'https://i.pravatar.cc/150?u=' . $this->faker->unique()->uuid,
            'linkedin' => $this->faker->optional()->url(),
            'website' => $this->faker->optional()->url(),
        ];
    }
}

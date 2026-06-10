<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        $title = $this->faker->unique()->sentence(rand(4, 8));

        $paragraphs = $this->faker->paragraphs(rand(4, 8));
        $content = collect($paragraphs)->map(function ($p) {
            $heading = rand(0, 1) ? '<h2>' . $this->faker->sentence(rand(3, 6)) . '</h2>' : '';
            return $heading . '<p>' . $p . '</p>';
        })->implode("\n");

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'excerpt' => $this->faker->sentences(2, true),
            'content' => $content,
            'featured_image' => null,
            'author_id' => User::factory(),
            'status' => 'published',
            'published_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn () => [
            'status' => 'draft',
            'published_at' => null,
        ]);
    }
}

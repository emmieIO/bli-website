<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class BlogPostSeeder extends Seeder
{
    public function run(): void
    {
        $author = User::where('email', 'markonuoha97@gmail.com')->first()
            ?? User::factory()->create([
                'name' => 'Nnaemeka Mark Onuoha',
                'email' => 'markonuoha97@gmail.com',
                'password' => 'Beaconinst@222',
            ]);

        Post::factory(12)->create([
            'author_id' => $author->id,
        ]);
    }
}

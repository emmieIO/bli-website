<?php

namespace Database\Seeders;

use App\Models\Speaker;
use App\Models\User;
use Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'markonuoha97@gmail.com'],
            [
                'name' => 'Nnaemeka Mark Onuoha',
                'email_verified_at' => now(),
                'password' => Hash::make('password'), // Change to a secure password
            ]
        );

        // Assign 'admin' role (assuming you use spatie/laravel-permission)
        if (method_exists($user, 'assignRole')) {
            $user->assignRole('admin');

            // You can use a factory to create related events for the admin user
            // $user->eventsCreated()->createMany(
            //     \App\Models\Event::factory()->count(20)->make()->toArray()
            // );

            $user->speakers()->createMany(
                Speaker::factory()->count(10)->make()->toArray()
            );
        }


    }
}

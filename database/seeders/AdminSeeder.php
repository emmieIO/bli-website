<?php

namespace Database\Seeders;

use App\Models\Speaker;
use App\Models\User;
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
                'phone' => '123-456-7890',
                'password' =>'password', // Change to a secure password
            ]
        );

        // Assign 'admin' role (assuming you use spatie/laravel-permission)
        if (method_exists($user, 'assignRole')) {
            $user->assignRole('admin');
        }

        $roles = ['instructor', 'student'];

        User::factory(4)->create()->each(function ($user) use ($roles) {
            $user->assignRole($roles[array_rand($roles)]);
        });
    }
}

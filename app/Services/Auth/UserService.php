<?php

namespace App\Services\Auth;

use App\Contracts\Auth\UserServiceInterface;
use App\Http\Requests\CreateUserRequest;
use App\Models\User;
use DB;
use Illuminate\Auth\Events\Registered;


class UserService implements UserServiceInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
    }

    public function createUser(CreateUserRequest $data, $role = "student")
    {
        // accept validated data
        $validated = $data->validated();

        // create user
        $user = DB::transaction(fn () => User::create($validated));

        // assign role to user if needed
        if ($role) {
            $user->assignRole($role);
        }

        // run user created event in order for role assign
        event(new Registered($user));
    }
}

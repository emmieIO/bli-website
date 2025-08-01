<?php

namespace App\Services\Auth;

use App\Contracts\Auth\UserServiceInterface;
use App\Http\Requests\CreateUserRequest;
use App\Models\User;
use Auth;
use DB;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Log;


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
        try {
            // accept validated data
            $validated = $data->validated();

            // create user
            $user = DB::transaction(fn() => User::create($validated));

            // assign role to user if needed
            if ($role) {
                $user->assignRole($role);
            }

            // run user created event in order for role assign
            event(new Registered($user));

            Auth::login($user);
            return $user;
        } catch (\Throwable $th) {
            Log::error('User creation failed', [
                'message' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);
            return false;
        }

    }

}

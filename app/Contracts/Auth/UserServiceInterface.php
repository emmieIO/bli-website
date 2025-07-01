<?php

namespace App\Contracts\Auth;

use App\Http\Requests\CreateUserRequest;

interface UserServiceInterface
{
    public function createUser(CreateUserRequest $data);
}

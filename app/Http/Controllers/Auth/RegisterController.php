<?php

namespace App\Http\Controllers\Auth;

use App\Contracts\Auth\UserServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function __construct(
        protected UserServiceInterface $userService
    ){}
    public function register()
    {
        return view('auth.register');
    }

    public function store(CreateUserRequest $request){
        $this->userService->createUser($request);
        redirect(to:route('login'))->with([
            'type' => "success",
            "message" => "Account Creation Success."
        ]);
    }
}

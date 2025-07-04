<?php

namespace App\Services\Auth;

use App\Http\Controllers\CourseController;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function loginUser(LoginRequest $request)
    {
        $credentials = $request->validated();
        $remember = $request->has('remember');

        // Use Auth::attempt for now, but structure for easy switch to attemptWhen
        $attempted = Auth::attempt([
            "email" => $credentials['email'],
            "password"=> $credentials['password']
        ], $remember);

        // In the future, you can replace the above with attemptWhen as needed:
        // $attempted = Auth::attemptWhen($credentials, $remember, function ($user) {
        //     // Add custom logic here
        //     return true;
        // });

        if ($attempted) {
            $request->session()->regenerate();
            return true;
        }

        return false;
    }

    public function logout(Request $request){
        try {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return true;
        } catch (\Exception $e) {
            // Optionally log the error or handle it as needed
            return false;
        }
    }
}

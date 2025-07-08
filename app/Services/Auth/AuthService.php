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

    public function loginUser(LoginRequest $request, string $guard='web')
    {
        $validated = $request->validated();
        $credentials = [
            'email'=>$validated['email'],
            'password'=>$validated['password']
        ];
        $remember = $request->has('remember');

        $attempted = Auth::guard($guard)->attempt($credentials, $remember);

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

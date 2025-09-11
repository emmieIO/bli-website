<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\UpdateUserProfileRequest;
use App\Models\User;
use App\Services\Auth\AuthService;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedController extends Controller
{
    public function __construct(
       protected AuthService $authService
    ){}
    public function login()
    {
        return view("auth.login");
    }
    public function authenticate(LoginRequest $request)
    {
        $user = User::withTrashed()->where('email', $request->input('email'))->first();

        if($user && $user->trashed()){
            return redirect()->back()->withErrors([
                'email' => 'Your account has been deactivated. Please contact support for assistance.'
            ])->onlyInput('email');
        }

        if ($this->authService->loginUser($request)) {
            return redirect()->intended(route("user_dashboard"))->with([
                "type" => "success",
                "message" => "Account Authenticated"
            ]);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showEmailVerificationNotice(){
        if (Auth::check() && Auth::user()->hasVerifiedEmail()) {
            return redirect(route('user_dashboard'))->with([
                'message' => "You are already verified.",
                "type" => 'info'
            ]);
        }
        return view('auth.verify-email');
    }

    public function showProfile(){
        $user = auth()->user();
        return view("user_dashboard.profile", compact('user'));
    }

    public function updatePersonalInfo(UpdateUserProfileRequest $request){
        if($this->authService->editPersonalInfo($request)){
            return redirect()->back()->with([
            'type' => 'success',
            'message' => 'Profile updated successfully.'
            ]);
        }
        return redirect()->back()->with([
            'type' => 'error',
            'message' => 'Failed to update profile. Please try again.'
        ]);
    }

    public function verifyEmail(Request $request){
        $user = Auth::user();

        if ($user && !$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        } else {
            session(['url.intended' => $request->fullUrl()]);
            return redirect(route('login'))->with([
                'message' => "You must be logged in to verify your email.",
                "type" => 'error'
            ]);
        }

        return redirect()->intended(route('user_dashboard'))->with([
            'message' => "Account verified",
            "type" => 'success'
        ]);
    }

    public function resendVerificationEmail(Request $request){
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return back()->with([
                'message' => "Your email address is already verified.",
                "type" => 'error'
            ]);
        }

        $user->sendEmailVerificationNotification();
        return back()->with([
            'message' => "A new verification link has been sent to your email address.",
            "type" => 'success'
        ]);
    }

    public function logout(Request $request)
    {
        if($this->authService->logout($request)){
            return redirect(route('homepage'))->with([
                "type" => "success",
                "message" => "Logged out successfully"
            ]);
        }
        return redirect()->back()->with([
            "type" => "error",
            "message" => "Logout failed. Please try again."
        ]);
    }

    public function destroyAccount(Request $request){
        $current_password = $request->validate([
            'current_password_destroy' => ['required', 'current_password'],
        ]);
        if($this->authService->distroyAccount($request)){
            return redirect(route('homepage'))->with([
                'type' => 'success',
                'message' => 'Account deleted successfully.'
            ]);
        }
        return redirect()->back()->with([
            'type' => 'error',
            'message' => 'Failed to delete account. Please try again.'
        ]);
    }
}

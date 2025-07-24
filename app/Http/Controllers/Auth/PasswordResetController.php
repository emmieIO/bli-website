<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\PasswordResetRequest;
use App\Services\Auth\PasswordResetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class PasswordResetController extends Controller
{
    public function __construct(protected PasswordResetService $passwordResetService){}

    public function forgotPassword(){
        return view("auth.forgot-password");
    }

    public function passwordReset(string $token){
        return view("auth.password-reset", compact('token'));
    }

    public function sendPasswordResetLink(ForgotPasswordRequest $request){
        $status = $this->passwordResetService->sendResetLink($request->only('email'));
        return $status === Password::RESET_LINK_SENT
            ? back()->with(['type' => "success", "message" => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    public function setNewPassword(PasswordResetRequest $request){
        $status = $this->passwordResetService->resetPassword($request->only('token', "email", 'password'));
        return $status === Password::PASSWORD_RESET
        ? redirect()->route('login')->with(['type' => "success", "message" => __($status)])
        : back()->withErrors(['type' => "error", "message" => __($status)]);
    }

    public function updatePassword(Request $request){
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if($this->passwordResetService->updatePassword($request)){
            return back()->with(['type' => 'success', 'message' => 'Password updated successfully.']);
        }
        return back()->withErrors(['type' => 'error', 'message' => 'Failed to update password.']);
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class AuthenticatedController extends Controller
{
    public function login()
    {
        return view("auth.login");
    }

    public function showEmailVerificationNotice(){
        return view('auth.verify-email');
    }

    public function showProfile(){
        return view("user_dashboard.profile");
    }

    public function verifyEmail(EmailVerificationRequest $request){
        $request->fulfill();

        return redirect(route('user_dashboard'))->with([
            'message' => "Acoount verified",
            "type" => 'success'
        ]);
    }

    public function resendVerificationMail(Request $request){
        $request->user()->sendEmailVerificationNotification();
        return back()->with([
            'message' => "Acoount verified",
            "type" => 'success'
        ]);
    }
}

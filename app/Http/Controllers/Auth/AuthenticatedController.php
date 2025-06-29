<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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
}

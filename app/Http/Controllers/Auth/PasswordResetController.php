<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PasswordResetController extends Controller
{
    public function forgotPassword(){
        return view("auth.forgot-password");
    }

    public function passwordReset(){
        return view("auth.password-reset");
    }
}

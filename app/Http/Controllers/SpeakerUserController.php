<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SpeakerUserController extends Controller
{
    public function  index(){
        return view('speakers.become-a-speaker');
    }
}

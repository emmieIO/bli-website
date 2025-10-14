<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(public UserService $service)
    {
    }
    public function updatePhoto(Request $request)
    {
        $user = auth()->user();
        if ($this->service->updatePhoto($user, $request->file('photo'))) {
            return redirect()->back()->with([
                'type' => 'success',
                'message' => 'Photo updated successfully.'
            ]);
        }

        return redirect()->back()->with([
            'type' => 'error',
            'message' => 'Failed to update photo. Please try again.'
        ]);

    }
}

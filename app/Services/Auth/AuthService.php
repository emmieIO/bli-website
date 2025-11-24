<?php

namespace App\Services\Auth;

use App\Http\Controllers\CourseController;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\UpdateUserProfileRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthService {
    /**
    * Create a new class instance.
    */

    public function __construct() {
        //
    }

    public function loginUser( LoginRequest $request, string $guard = 'web' ) {
        $validated = $request->validated();
        $credentials = [
            'email'=>$validated[ 'email' ],
            'password'=>$validated[ 'password' ]
        ];

        $user = User::where('email', $validated['email'])->first();

        $remember = $request->has( 'remember' );

        $attempted = Auth::guard( $guard )->attempt( $credentials, $remember );


        if ( $attempted ) {
            $request->session()->regenerate();
            return true;
        }

        return false;
    }

    public function editPersonalInfo( UpdateUserProfileRequest $request ) {
        try {
            $validated = $request->validated();
            $password = $validated[ 'current_password' ];
            $user = auth()->user();
            // check if password matches signed in user password
            if ( !Hash::check( $password, ( string ) $user->password ) ) {
                \Log::info( 'User attempted to edit personal info with incorrect password.', [
                    'user_id' => auth()->id(),
                    'email' => auth()->user() ? auth()->user()->email : null,
                    'ip' => $request->ip(),
                ] );
                return false;
            }
            if ( $user->hasAnyRole( [ 'student', 'instructor', 'user' ] ) ) {
                if ( $validated[ 'email' ] != $user->email ) {
                    \Log::info( 'User attempted to change email address.', [
                        'user_id' => $user->getKey(),
                        'old_email' => $user->email,
                        'new_email' => $validated[ 'email' ],
                        'ip' => $request->ip(),
                    ] );
                    return false;
                }
            }
            $user->update( [
                'name' => $validated[ 'name' ],
                'phone' => $validated[ 'phone' ],
                'email' => $validated[ 'email' ]
            ] );
            return true;

        } catch ( \Throwable $th ) {
            \Log::error( 'Error updating personal info: ' . $th->getMessage() );
            return false;
        }
    }

    public function destroyAccount(Request $request)
    {
        try {
            $user = auth()->user();
            if (!$user) {
                return false;
            }

            $password = $request->input('current_password_destroy');
            if (!$password || !Hash::check($password, (string) $user->password)) {
                \Log::info('User attempted to delete account with incorrect password.', [
                    'user_id' => $user->getKey(),
                    'email' => $user->email,
                    'ip' => $request->ip(),
                ]);
                return false;
            }

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $user->delete();

            return true;
        } catch (\Throwable $th) {
            \Log::error('Error deleting user account: ' . $th->getMessage());
            return false;
        }
    }

    public function logout( Request $request ) {
        try {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return true;
        } catch ( \Exception $e ) {
            // Optionally log the error or handle it as needed
            return false;
        }
    }
}

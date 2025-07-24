<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use PHPUnit\Framework\TestStatus\Success;

class PasswordResetService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function sendResetLink($email){
        $status = Password::sendResetLink($email);

        return $status;
    }

    public function resetPassword(array $credentials){
        try {
            $status = Password::reset(
                $credentials,
                function(User $user, string $password){
                    $user->forceFill([
                        "password" => $password
                    ])->setRememberToken(Str::random(60));

                    $user->save();
                    event(new PasswordReset($user));
                });

            return $status;
        } catch (\Throwable $th) {
            Log::error('User creation failed', [
                'message' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);
            return false;
        }

    }

public function updatePassword(Request $request)
{
    try {
        $user = auth()->user();
        if (!Hash::check($request->input('current_password'), $user->password)) {
            Log::warning('Password update failed: current password does not match', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);
            return false;
        }

        $user->update([
            'password' => Hash::make($request->input('password'))
        ]);

        return true;

    } catch (\Throwable $th) {
        Log::error('Password update failed: ' . $th->getMessage(), [
            'user_id' => auth()->id(),
            'trace' => $th->getTraceAsString(),
        ]);
        return false;
    }
}

}

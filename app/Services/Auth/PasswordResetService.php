<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
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
}

<?php

namespace App\Services\Auth;

use App\Contracts\Auth\UserServiceInterface;
use App\Http\Requests\CreateUserRequest;
use App\Models\User;
use App\Traits\HasFileUpload;
use Auth;
use DB;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;


class UserService implements UserServiceInterface
{
    use HasFileUpload;
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
    }

    public function createUser(CreateUserRequest $data, $role = "student")
    {
        try {
            // accept validated data
            $validated = $data->validated();

            // create user
            $user = DB::transaction(fn() => User::create($validated));

            // assign role to user if needed
            if ($role) {
                $user->assignRole($role);
            }

            // run user created event in order for role assign
            event(new Registered($user));

            Auth::login($user);
            return $user;
        } catch (\Throwable $th) {
            Log::error('User creation failed', [
                'message' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);
            return false;
        }

    }

    public function updatePhoto(User $user, UploadedFile $profilePhoto)
    {
        $oldPhoto = $user->photo;
        try {
            return DB::transaction(function () use ($user, $profilePhoto, $oldPhoto) {
                if ($profilePhoto) {
                    $newPhoto = $this->uploadFile($profilePhoto, 'user_photos');
                    if ($newPhoto) {
                        if ($oldPhoto) {
                            $this->deleteFile($oldPhoto);
                        }
                        // update user photo path
                        $user->photo = $newPhoto;
                        $user->save();
                        return true;
                    }
                    return false;
                }
            });
        } catch (\Exception $e) {
            // Log the exception message for debugging
            Log::error('Failed to update user photo: ' . $e->getMessage());
            return false;
        }

    }


}

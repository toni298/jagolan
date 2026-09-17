<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;

class UserService
{
    public function __construct(
        protected ImageService $imageService
    ) {}

    public function create(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $data['password'] = Hash::make($data['password']);
            $data['role'] = 'admin';

            if (isset($data['avatar']) && $data['avatar'] instanceof UploadedFile) {
                $data['avatar'] = $this->imageService->optimizeAndStore($data['avatar'], 'users/avatars');
            }

            $user = User::create($data);
            
            Log::info('User created', [
                'user_id' => Auth::id(),
                'created_user_id' => $user->id,
                'created_user_email' => $user->email,
            ]);

            return $user;
        });
    }

    public function update(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data) {
            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            if (isset($data['avatar']) && $data['avatar'] instanceof UploadedFile) {
                $data['avatar'] = $this->imageService->optimizeAndStore($data['avatar'], 'users/avatars');
            }

            $user->update($data);
            
            Log::info('User updated', [
                'user_id' => Auth::id(),
                'updated_user_id' => $user->id,
                'updated_user_email' => $user->email,
            ]);

            return $user;
        });
    }

    public function delete(User $user): bool
    {
        if (Auth::id() === $user->id) {
            return false;
        }

        return DB::transaction(function () use ($user) {
            $deleted = $user->delete();
            
            if ($deleted) {
                Log::info('User deleted', [
                    'user_id' => Auth::id(),
                    'deleted_user_id' => $user->id,
                    'deleted_user_email' => $user->email,
                ]);
            }

            return $deleted;
        });
    }
}

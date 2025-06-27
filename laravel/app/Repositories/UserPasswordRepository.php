<?php

namespace App\Repositories;

use App\Models\UserPassword;

class UserPasswordRepository
{
    public function create(array $data): UserPassword
    {
        return UserPassword::create($data);
    }

    public function getLastPasswordsByUser(string $userId, int $limit)
    {
        return UserPassword::where('user_id', $userId)
        ->orderBy('created_at', 'desc')
        ->limit($limit)
        ->pluck('password');
    }
}

<?php

namespace App\Repositories;

use App\Models\UserPassword;

class UserPasswordRepository
{
    public function create(array $data): UserPassword
    {
        return UserPassword::create($data);
    }
}

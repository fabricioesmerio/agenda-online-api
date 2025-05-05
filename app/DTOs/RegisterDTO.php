<?php

namespace App\DTOs;

class RegisterDTO
{
    public function __construct(
        public string $tenantName,
        public string $name,
        public string $email,
        public string $password
    ) {}
}

<?php

namespace App\Services;

use App\DTOs\RegisterDTO;
use App\Repositories\TenantRepository;
use App\Repositories\UserPasswordRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class RegisterService
{

    public function __construct(
        protected TenantRepository $tenantRepository,
        protected UserRepository $userRepository,
        protected UserPasswordRepository $userPasswordRepository,
    ) {}

    public function register(RegisterDTO $dto): array
    {
        DB::beginTransaction();
        try {
            $this->checkEmailUnique($dto->email);

            $tenant = $this->tenantRepository->create([
                'id' => (string) Str::uuid(),
                'name' => $dto->tenantName,
                'active' => true,
            ]);

            $user = $this->userRepository->create([
                'name' => $dto->name,
                'email' => $dto->email,
                'tenant_id' => $tenant->id,
                'password' => Hash::make($dto->password),
                'active' => true,
            ]);

            $this->userPasswordRepository->create([
                'user_id' => $user->id,
                'password' => $user->password,
            ]);

            DB::commit();

            return [
                'tenant' => $tenant,
                'user' => $user,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function checkEmailUnique(string $email)
    {
        $user = $this->userRepository->findByEmail($email);

        if ($user) {
            throw ValidationException::withMessages([
                'email' => ['Este e-mail já está em uso.'],
            ]);
        }
    }
}

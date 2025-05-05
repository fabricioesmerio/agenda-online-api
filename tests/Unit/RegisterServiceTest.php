<?php

namespace Tests\Unit;

use App\DTOs\RegisterDTO;
use App\Models\Tenant;
use App\Models\User;
use App\Repositories\TenantRepository;
use App\Repositories\UserPasswordRepository;
use App\Repositories\UserRepository;
use App\Services\RegisterService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class RegisterServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_registers_a_new_tenant_and_user()
    {
        $service = new RegisterService(
            new TenantRepository(),
            new UserRepository(),
            new UserPasswordRepository()
        );

        $dto = new RegisterDTO(
            tenantName: 'Empresa Teste',
            name: 'João da Silva',
            email: 'joao@example.com',
            password: 'senha123'
        );

        $result = $service->register($dto);

        $this->assertDatabaseHas('tenants', [
            'name' => 'Empresa Teste',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'joao@example.com',
            'tenant_id' => $result['tenant']->id,
        ]);

        $this->assertDatabaseHas('user_passwords', [
            'user_id' => $result['user']->id,
        ]);

        $this->assertTrue(Hash::check('senha123', $result['user']->password));
    }

    public function test_register_fails_when_email_is_duplicate()
    {
        $tenant = Tenant::create([
            'name' => 'Test Tenant',
            'active' => true,
        ]);

        User::create([
            'name' => 'Existing User',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'tenant_id' => $tenant->id,
        ]);


        $dto = new RegisterDTO(
            name: 'User Test',
            email: 'test@example.com',
            password: 'password123',
            tenantName: 'Test Tenant',
        );

        // Criar um usuário com o mesmo e-mail

        $registerService = app(RegisterService::class);

        // Teste se lançar exceção quando e-mail duplicado
        $this->expectException(ValidationException::class);

        $registerService->register($dto);
    }
}

<?php

namespace App\Http\Controllers;

use App\DTOs\RegisterDTO;
use App\Services\RegisterService;
use App\Http\Requests\RegisterRequest;

class AuthController extends Controller
{
    public function register(RegisterRequest $request, RegisterService $registerService)
    {
        $dto = new RegisterDTO(
            tenantName: $request->tenant_name,
            name: $request->name,
            email: $request->email,
            password: $request->password
        );

        try {

            $result = $registerService->register($dto);

            $token = auth('api')->login($result['user']);

            return response()->json([
                'message' => 'Usuário e Tenant criados com sucesso',
                'tenant' => $result['tenant'],
                'user' => $result['user'],
                'token' => $token,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

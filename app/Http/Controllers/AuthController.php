<?php

namespace App\Http\Controllers;

use App\DTOs\RegisterDTO;
use App\Services\RegisterService;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

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

            $credentials = [
                'email' => $dto->email,
                'password' => $dto->password,
            ];

            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Não autorizado'], 401);
            }

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

    public function refresh(Request $request)
    {
        // Tenta obter o token atual (mesmo expirado)
        $token = $request->bearerToken();

        if (! $token) {
            return response()->json(['error' => 'Token não fornecido'], Response::HTTP_UNAUTHORIZED);
        }

        try {
            $newToken = JWTAuth::parseToken()->refresh();
            return response()->json(['token' => $newToken]);
        } catch (TokenExpiredException $e) {
            return response()->json(['error' => 'Token expirado e não pode ser renovado'], 401);
        } catch (TokenInvalidException $e) {
            return response()->json(['error' => 'Token inválido ' . $e->getMessage()], 401);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Token não encontrado'], 401);
        }

        return response()->json([
            'token' => $newToken
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\DTOs\RegisterDTO;
use App\Services\RegisterService;
use App\Http\Requests\RegisterRequest;
use App\Mail\ForgotPasswordMail;
use App\Models\PasswordReset;
use App\Models\User;
use App\Repositories\UserPasswordRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    public function __construct(
        protected UserPasswordRepository $userPasswordRepository
    ) {}

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

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $user = User::where('email', $request->email)->first();

        if ($user) {
            PasswordReset::where('expires_at', '<', now())->delete();
            $token = Str::uuid();
            PasswordReset::updateOrCreate(
                ['email' => $user->email],
                [
                    'token' => $token,
                    'expires_at' => now()->addMinutes(30),
                ]
            );
            Mail::to($user->email)->send(new ForgotPasswordMail($token));
        }

        return response()->json(['message' => 'Se este e-mail estiver cadastrado em nosso sistema, você receberá uma mensagem com as instruções em instantes.']);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'password' => 'required|min:6'
        ]);

        $reset = PasswordReset::where('token', $request->token)->first();

        if (!$reset || $reset->expires_at < now()) {
            return response()->json(['message' => 'Token inválido ou expirado'], 400);
        }

        $user = User::where('email', $reset->email)->first();

        if (!$user) {
            return response()->json(['message' => 'Usuário não encontrado.'], 404);
        }

        $password_hash = Hash::make($request->password);
        $historyLimit = 5;

        $recentPasswords = $this->userPasswordRepository->getLastPasswordsByUser($user->id, $historyLimit);

        foreach ($recentPasswords as $oldHashedPassword) {
            if (Hash::check($request->password, $oldHashedPassword)) {
                throw ValidationException::withMessages([
                    'password' => 'Você não pode reutilizar uma das suas últimas ' . $historyLimit . ' senhas.',
                ]);
            }
        }


        $user->update(['password' => $password_hash]);

        $this->userPasswordRepository->create([
            'user_id' => $user->id,
            'password' => $user->password,
        ]);

        $reset->delete();
        PasswordReset::where('expires_at', '<', now())->delete();

        return response()->json(['message' => 'Senha redefinida com sucesso.']);
    }
}

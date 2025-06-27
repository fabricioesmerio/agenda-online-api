<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StatusController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', function (Request $request) {
    $credentials = $request->only(['email', 'password']);

    if (!$token = auth('api')->attempt($credentials)) {
        abort(400, 'E-mail e/ou senha incorretos');
    }

    return response()->json([
        'token' => $token,
        'user' => auth('api')->user()
    ]);
});

Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('jwt.refresh');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->middleware('throttle:3,5');
Route::post('/reset-password', [AuthController::class, 'resetPassword']);


Route::middleware('auth:api')->group(function () {
    Route::get('/appointments', [AppointmentController::class, 'index']);
    Route::post('/appointments', [AppointmentController::class, 'store']);
    Route::put('/appointments/{id}', [AppointmentController::class, 'update']);
    Route::delete('/appointments/{id}', [AppointmentController::class, 'delete']);
});

Route::middleware('auth:api')->group(function () {
    Route::post('/status', [StatusController::class, 'store']);
    Route::get('/status', [StatusController::class, 'index']);
    Route::get('/status/{id}', [StatusController::class, 'getById']);
    Route::put('/status/{id}', [StatusController::class, 'update']);
});

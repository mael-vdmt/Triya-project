<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

class LoginController extends Controller
{
    public function __construct(
        private AuthService $authService
    ) {}

    public function login(LoginRequest $request): JsonResponse
    {
        // Connecter l'utilisateur via AuthService
        $result = $this->authService->login(
            $request->email,
            $request->password
        );

        return response()->json([
            'message' => 'Connexion réussie',
            'user' => new UserResource($result['user']),
            'token' => $result['token'],
            'token_type' => 'Bearer'
        ]);
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    public function __construct(
        private AuthService $authService
    ) {}

    public function register(RegisterRequest $request): JsonResponse
    {
        // Inscrire l'utilisateur via AuthService
        $result = $this->authService->register($request->validated());

        return response()->json([
            'message' => 'Utilisateur inscrit avec succès',
            'user' => new UserResource($result['user']),
            'token' => $result['token'],
            'token_type' => 'Bearer'
        ], 201);
    }
}

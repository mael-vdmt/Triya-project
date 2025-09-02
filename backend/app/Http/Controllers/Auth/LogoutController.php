<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    public function __construct(
        private AuthService $authService
    ) {}

    public function logout(Request $request): JsonResponse
    {
        // Déconnecter l'utilisateur via AuthService
        $this->authService->logout($request->user());

        return response()->json([
            'message' => 'Déconnexion réussie'
        ]);
    }
}

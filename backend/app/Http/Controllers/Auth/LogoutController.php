<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    public function logout(): JsonResponse
    {
        Auth::logout();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }
}

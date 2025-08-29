<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function user(): JsonResponse
    {
        return response()->json([
            'user' => new UserResource(Auth::user())
        ]);
    }
}

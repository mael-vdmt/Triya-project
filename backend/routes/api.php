<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Test route
Route::get('/test', function () {
    return response()->json(['message' => 'API is working!']);
});

// CSRF cookie route for SPA
Route::get('/sanctum/csrf-cookie', function () {
    return response()->json(['message' => 'CSRF cookie set']);
});

// Guest routes
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [LogoutController::class, 'logout']);
    Route::get('/user', [UserController::class, 'user']);
    
    // User routes
    Route::apiResource('users', UserController::class);
    
    // Club routes
    Route::get('/clubs/search', [ClubController::class, 'search']);
    Route::get('/clubs/{id}/members', [ClubController::class, 'members']);
    Route::post('/clubs/{id}/members', [ClubController::class, 'addMember']);
    Route::delete('/clubs/{id}/members', [ClubController::class, 'removeMember']);
    Route::apiResource('clubs', ClubController::class);
    
    // Event routes
    Route::get('/events/search', [EventController::class, 'search']);
    Route::get('/clubs/{id}/events', [EventController::class, 'clubEvents']);
    Route::post('/events/{id}/approve', [EventController::class, 'approve']);
    Route::post('/events/{id}/reject', [EventController::class, 'reject']);
    Route::post('/events/{id}/formats', [EventController::class, 'addFormat']);
    Route::delete('/events/formats/{formatId}', [EventController::class, 'removeFormat']);
    Route::post('/events/{id}/register', [EventController::class, 'register']);
    Route::delete('/events/{id}/unregister', [EventController::class, 'unregister']);
    Route::post('/events/{id}/carpooling', [EventController::class, 'addCarpooling']);
    Route::post('/events/{id}/accommodation', [EventController::class, 'addAccommodation']);
    Route::apiResource('events', EventController::class);
});

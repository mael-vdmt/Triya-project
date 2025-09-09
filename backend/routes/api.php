<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\ClubInvitationController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Route de test
Route::get('/test', function () {
    return response()->json(['message' => 'API is working!']);
});

// Route CSRF cookie pour SPA
Route::get('/sanctum/csrf-cookie', function () {
    return response()->json(['message' => 'CSRF cookie set']);
});

// Routes publiques
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);
Route::get('/invitation/{token}', [ClubInvitationController::class, 'show']);
Route::post('/invitation/{token}/accept', [ClubInvitationController::class, 'accept']);

// Routes protégées
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [LogoutController::class, 'logout']);
    Route::get('/user', [UserController::class, 'user']);
    Route::get('/user/clubs', [UserController::class, 'clubs']);
    
    // Routes des utilisateurs
    Route::apiResource('users', UserController::class);
    
    // Routes des clubs
    Route::get('/clubs/{id}/members', [ClubController::class, 'members']);
    Route::post('/clubs/{id}/members', [ClubController::class, 'addMember']);
    Route::delete('/clubs/{id}/members', [ClubController::class, 'removeMember']);
    Route::apiResource('clubs', ClubController::class);
    
    // Routes des invitations de club
    Route::get('/clubs/{clubId}/invitations', [ClubInvitationController::class, 'index']);
    Route::post('/clubs/{clubId}/invitations', [ClubInvitationController::class, 'store']);
    Route::post('/clubs/{clubId}/invitations/link', [ClubInvitationController::class, 'createLink']);
    Route::delete('/invitations/{invitationId}', [ClubInvitationController::class, 'destroy']);
    
    // Routes des événements
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
    
    // Routes des groupes
    Route::get('/groups/search', [GroupController::class, 'search']);
    Route::get('/groups/{id}/members', [GroupController::class, 'members']);
    Route::post('/groups/{id}/members', [GroupController::class, 'addMember']);
    Route::delete('/groups/{id}/members', [GroupController::class, 'removeMember']);
    Route::apiResource('groups', GroupController::class);
    
    // Routes des documents
    Route::get('/documents/search', [DocumentController::class, 'search']);
    Route::get('/documents/accessible', [DocumentController::class, 'accessible']);
    Route::get('/documents/{id}/download', [DocumentController::class, 'download']);
    Route::apiResource('documents', DocumentController::class);
});

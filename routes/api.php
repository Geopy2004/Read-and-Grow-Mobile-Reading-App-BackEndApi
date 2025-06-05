<?php

use App\Http\Controllers\API\AuthenticationController;
use Illuminate\Support\Facades\Route;

Route::post('/admin/register', [AuthenticationController::class, 'registerAdmin']);
Route::post('/teacher/register', [AuthenticationController::class, 'registerTeacher']);
Route::post('/student/register', [AuthenticationController::class, 'registerStudent']);

Route::post('/login', [AuthenticationController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthenticationController::class, 'logout']);

// Admin-only route
Route::middleware('auth:sanctum')->get('/users', [AuthenticationController::class, 'getUsers']);
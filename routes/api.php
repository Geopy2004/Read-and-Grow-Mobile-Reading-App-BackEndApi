<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthenticationController;
use App\Http\Controllers\API\AdminApiController;
use App\Http\Controllers\API\TeacherApiController;
use App\Http\Controllers\API\StudentApiController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Registration routes
Route::post('/admin/register', [AuthenticationController::class, 'registerAdmin']);
Route::post('/teacher/register', [AuthenticationController::class, 'registerTeacher']);
Route::post('/student/register', [AuthenticationController::class, 'registerStudent']);

// Login routes
Route::post('/login', [AuthenticationController::class, 'login']);
Route::post('/admin/login', [AuthenticationController::class, 'adminLogin']);
Route::post('/teacher/login', [AuthenticationController::class, 'teacherLogin']);
Route::post('/student/login', [AuthenticationController::class, 'studentLogin']);

/*
|--------------------------------------------------------------------------
| Protected Routes (Authenticated via Sanctum)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    // Logout routes
    Route::post('/logout', [AuthenticationController::class, 'logout']);
    Route::post('/admin/logout', [AuthenticationController::class, 'adminLogout']);
    Route::post('/teacher/logout', [AuthenticationController::class, 'teacherLogout']);
    Route::post('/student/logout', [AuthenticationController::class, 'studentLogout']);

    // Admin: View all users
    Route::get('/users', [AuthenticationController::class, 'getUsers']);

    /*
    |--------------------------------------------------------------------------
    | Admin Modules
    |--------------------------------------------------------------------------
    */

    // Admin CRUD
    Route::prefix('admins')->group(function () {
        Route::get('/', [AdminApiController::class, 'index']);
        Route::post('/', [AdminApiController::class, 'store']);
        Route::get('/{id}', [AdminApiController::class, 'show']);
        Route::put('/{id}', [AdminApiController::class, 'update']);
        Route::delete('/{id}', [AdminApiController::class, 'destroy']);

        // Manage teachers
        Route::prefix('teachers')->group(function () {
            Route::get('/', [TeacherApiController::class, 'index']);
            Route::post('/', [TeacherApiController::class, 'store']);
            Route::get('/{id}', [TeacherApiController::class, 'show']);
            Route::put('/{id}', [TeacherApiController::class, 'update']);
            Route::delete('/{id}', [TeacherApiController::class, 'destroy']);
        });

        // Manage students
        Route::prefix('students')->group(function () {
            Route::get('/', [StudentApiController::class, 'index']);
            Route::post('/', [StudentApiController::class, 'store']);
            Route::get('/{id}', [StudentApiController::class, 'show']);
            Route::put('/{id}', [StudentApiController::class, 'update']);
            Route::delete('/{id}', [StudentApiController::class, 'destroy']);
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Teacher Modules
    |--------------------------------------------------------------------------
    */

    Route::prefix('teacher')->group(function () {
        Route::get('/students', [StudentApiController::class, 'index']);
        Route::post('/students', [StudentApiController::class, 'store']);
        Route::get('/students/{id}', [StudentApiController::class, 'show']);
        Route::put('/students/{id}', [StudentApiController::class, 'update']);
        Route::delete('/students/{id}', [StudentApiController::class, 'destroy']);
    });

    /*
    |--------------------------------------------------------------------------
    | Student Modules
    |--------------------------------------------------------------------------
    */

    Route::prefix('student')->group(function () {
        // Define your student-specific routes here
        // For example:
        // Route::get('/profile', [StudentApiController::class, 'profile']);
    });
});

<?php

<<<<<<< HEAD
use App\Http\Controllers\API\AuthenticationController;
use Illuminate\Support\Facades\Route;

=======
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthenticationController;
use App\Http\Controllers\API\AdminApiController;
use App\Http\Controllers\API\TeacherApiController;
use App\Http\Controllers\API\StudentApiController;
/*
|--------------------------------------------------------------------------
| Public Routes (Unauthenticated)
|--------------------------------------------------------------------------
*/

// Registration routes
>>>>>>> 131317a (Initial commit of Read-and-Grow backend)
Route::post('/admin/register', [AuthenticationController::class, 'registerAdmin']);
Route::post('/teacher/register', [AuthenticationController::class, 'registerTeacher']);
Route::post('/student/register', [AuthenticationController::class, 'registerStudent']);

<<<<<<< HEAD
Route::post('/login', [AuthenticationController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthenticationController::class, 'logout']);

// Admin-only route
Route::middleware('auth:sanctum')->get('/users', [AuthenticationController::class, 'getUsers']);
=======
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

    // Admin modules - View all users (all roles)
    Route::get('/users', [AuthenticationController::class, 'getUsers']);

    // Admin CRUD routes
    Route::prefix('admins')->group(function () {
        Route::get('/', [AdminApiController::class, 'index']);
        Route::post('/', [AdminApiController::class, 'store']);
        Route::get('/{id}', [AdminApiController::class, 'show']);
        Route::put('/{id}', [AdminApiController::class, 'update']);
        Route::delete('/{id}', [AdminApiController::class, 'destroy']);
    });

    // Teacher CRUD routes for admins only
    Route::prefix('admins/teachers')->group(function () {
        Route::get('/', [TeacherApiController::class, 'index']);
        Route::post('/', [TeacherApiController::class, 'store']);
        Route::get('/{id}', [TeacherApiController::class, 'show']);
        Route::put('/{id}', [TeacherApiController::class, 'update']);
        Route::delete('/{id}', [TeacherApiController::class, 'destroy']);
    });

    // Student CRUD routes for admins only
    Route::prefix('admins/students')->group(function () {
        Route::get('/', [StudentApiController::class, 'index']);
        Route::post('/', [StudentApiController::class, 'store']);
        Route::get('/{id}', [StudentApiController::class, 'show']);
        Route::put('/{id}', [StudentApiController::class, 'update']);
        Route::delete('/{id}', [StudentApiController::class, 'destroy']);
    });


// Teachers modules routes 
Route::middleware('auth:sanctum')->prefix('teacher')->group(function () {
    
    // List all students belonging to the logged-in teacher
    Route::get('/students', [StudentApiController::class, 'index']);
    Route::get('/students/{id}', [StudentApiController::class, 'show']);
    Route::put('/students/{id}', [StudentApiController::class, 'update']);
    Route::post('/students', [StudentApiController::class, 'store']);
    Route::delete('/students/{id}', [StudentApiController::class, 'destroy']);
    });

});
//Students modules routes 
>>>>>>> 131317a (Initial commit of Read-and-Grow backend)

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class AuthenticationController extends Controller
{
    public function registerAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:users,username',
            'admin_email' => 'required|email|unique:admins,admin_email',
            'admin_password' => 'required|min:6',
            'admin_security_code' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::create([
                'username' => $request->username,
                'password' => Hash::make($request->admin_password),
                'role' => 'admin'
            ]);

            $user->admin()->create([
                'admin_email' => $request->admin_email,
                'admin_security_code' => Hash::make($request->admin_security_code)
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Admin registered successfully'
            ], 201);

        } catch (\Exception $e) {
            Log::error('Admin registration failed: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Registration failed'
            ], 500);
        }
    }

    public function registerTeacher(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:users,username',
            'teacher_email' => 'required|email|unique:teachers,teacher_email',
            'teacher_password' => 'required|min:6',
            'teacher_name' => 'required',
            'teacher_position' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::create([
                'username' => $request->username,
                'password' => Hash::make($request->teacher_password),
                'role' => 'teacher'
            ]);

            $user->teacher()->create([
                'teacher_name' => $request->teacher_name,
                'teacher_email' => $request->teacher_email,
                'teacher_position' => $request->teacher_position
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Teacher registered successfully'
            ], 201);

        } catch (\Exception $e) {
            Log::error('Teacher registration failed: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Registration failed'
            ], 500);
        }
    }

    public function registerStudent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:users,username',
            'student_password' => 'required|min:6',
            'student_name' => 'required',
            'student_lrn' => 'required|unique:students,student_lrn',
            'student_grade' => 'required',
            'student_section' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::create([
                'username' => $request->username,
                'password' => Hash::make($request->student_password),
                'role' => 'student'
            ]);

            $user->student()->create([
                'student_name' => $request->student_name,
                'student_lrn' => $request->student_lrn,
                'student_grade' => $request->student_grade,
                'student_section' => $request->student_section
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Student registered successfully'
            ], 201);

        } catch (\Exception $e) {
            Log::error('Student registration failed: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Registration failed'
            ], 500);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::where('username', $request->username)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid credentials'
                ], 401);
            }

            $user->tokens()->delete();
            $token = $user->createToken('auth-token')->plainTextToken;

            return response()->json([
                'status' => 'success',
                'message' => 'Login successful',
                'token' => $token,
                'user' => $user->load([$user->role])
            ]);

        } catch (\Exception $e) {
            Log::error('Login failed: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Login failed'
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            if (!$request->user()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No authenticated user'
                ], 401);
            }

            $request->user()->tokens()->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Logged out successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Logout failed: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Logout failed'
            ], 500);
        }
    }

    public function getUsers(Request $request)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json([
                'status' => 'error',
                'message' => 'Forbidden'
            ], 403);
        }

        return response()->json(User::all());
    }
}

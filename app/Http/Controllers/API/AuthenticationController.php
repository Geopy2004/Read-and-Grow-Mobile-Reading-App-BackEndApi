<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
<<<<<<< HEAD
use Illuminate\Validation\ValidationException;
=======
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
>>>>>>> 131317a (Initial commit of Read-and-Grow backend)
use App\Models\User;

class AuthenticationController extends Controller
{
<<<<<<< HEAD
    public function registerAdmin(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'username' => 'required|unique:users',
            'admin_email' => 'required|email|unique:users,admin_email',
            'admin_password' => 'required|min:6|confirmed',
=======
    // Admin Registration
    public function registerAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:users,username',
            'admin_email' => 'required|email|unique:admins,admin_email',
            'admin_password' => 'required|min:6',
>>>>>>> 131317a (Initial commit of Read-and-Grow backend)
            'admin_security_code' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
<<<<<<< HEAD
=======
                'status' => 'error',
>>>>>>> 131317a (Initial commit of Read-and-Grow backend)
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

<<<<<<< HEAD
        $user = User::create([
            'username' => $request->username,
            'admin_email' => $request->admin_email,
            'admin_security_code' => $request->admin_security_code,
            'password' => bcrypt($request->admin_password),
            'role' => 'admin'
        ]);

        return response()->json(['message' => 'Admin registered'], 201);
    }

    public function registerTeacher(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'teacher_username' => 'required|unique:users,username',
            'teacher_email' => 'required|email|unique:users,teacher_email',
            'teacher_password' => 'required|min:6|confirmed',
=======
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

    // Teacher Registration
    public function registerTeacher(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:users,username',
            'teacher_email' => 'required|email|unique:teachers,teacher_email',
            'teacher_password' => 'required|min:6',
>>>>>>> 131317a (Initial commit of Read-and-Grow backend)
            'teacher_name' => 'required',
            'teacher_position' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
<<<<<<< HEAD
=======
                'status' => 'error',
>>>>>>> 131317a (Initial commit of Read-and-Grow backend)
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

<<<<<<< HEAD
        $user = User::create([
            'username' => $request->teacher_username,
            'teacher_email' => $request->teacher_email,
            'teacher_name' => $request->teacher_name,
            'teacher_position' => $request->teacher_position,
            'password' => bcrypt($request->teacher_password),
            'role' => 'teacher'
        ]);

        return response()->json(['message' => 'Teacher registered'], 201);
    }

    public function registerStudent(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'student_username' => 'required|unique:users,username',
            'student_password' => 'required|min:6|confirmed',
            'student_name' => 'required',
            'student_lrn' => 'required|unique:users,student_lrn',
=======
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

    // Student Registration
    public function registerStudent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:users,username',
            'student_password' => 'required|min:6',
            'student_name' => 'required',
            'student_lrn' => 'required|unique:students,student_lrn',
>>>>>>> 131317a (Initial commit of Read-and-Grow backend)
            'student_grade' => 'required',
            'student_section' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
<<<<<<< HEAD
=======
                'status' => 'error',
>>>>>>> 131317a (Initial commit of Read-and-Grow backend)
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

<<<<<<< HEAD
        $user = User::create([
            'username' => $request->student_username,
            'student_name' => $request->student_name,
            'student_lrn' => $request->student_lrn,
            'student_grade' => $request->student_grade,
            'student_section' => $request->student_section,
            'password' => bcrypt($request->student_password),
            'role' => 'student'
        ]);

        return response()->json(['message' => 'Student registered'], 201);
    }

    public function login(Request $request)
    {
        // Validate input fields
        $validator = \Validator::make($request->all(), [
=======
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

    // Generic Login
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
>>>>>>> 131317a (Initial commit of Read-and-Grow backend)
            'username' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
<<<<<<< HEAD
=======
                'status' => 'error',
>>>>>>> 131317a (Initial commit of Read-and-Grow backend)
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

<<<<<<< HEAD
        $user = User::where('username', $request->username)->first();

        if (! $user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if (! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Incorrect password'], 401);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'role' => $user->role,
            'user' => $user
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    }

    public function getUsers(Request $request)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return response()->json(User::all());
=======
        try {
            $user = User::where('username', $request->username)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid credentials'
                ], 401);
            }

            // Revoke all previous tokens
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

    // Student Login
    public function studentLogin(Request $request)
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
            $user = User::where('username', $request->username)
                        ->where('role', 'student')
                        ->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid student credentials'
                ], 401);
            }

            // Revoke all previous tokens
            $user->tokens()->delete();

            $token = $user->createToken('student-token', ['student'])->plainTextToken;

            return response()->json([
                'status' => 'success',
                'message' => 'Student login successful',
                'token' => $token,
                'user' => $user->load('student')
            ]);

        } catch (\Exception $e) {
            Log::error('Student login failed: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Login failed'
            ], 500);
        }
    }

    // Teacher Login
    public function teacherLogin(Request $request)
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
            $user = User::where('username', $request->username)
                        ->where('role', 'teacher')
                        ->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid teacher credentials'
                ], 401);
            }

            // Revoke all previous tokens
            $user->tokens()->delete();

            $token = $user->createToken('teacher-token', ['teacher'])->plainTextToken;

            return response()->json([
                'status' => 'success',
                'message' => 'Teacher login successful',
                'token' => $token,
                'user' => $user->load('teacher')
            ]);

        } catch (\Exception $e) {
            Log::error('Teacher login failed: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Login failed'
            ], 500);
        }
    }

    // Admin Login
    public function adminLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
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
            $user = User::where('username', $request->username)
                        ->where('role', 'admin')
                        ->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid admin credentials'
                ], 401);
            }

            if (!Hash::check($request->admin_security_code, $user->admin->admin_security_code)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid security code'
                ], 401);
            }

            // Revoke all previous tokens
            $user->tokens()->delete();

            $token = $user->createToken('admin-token', ['admin'])->plainTextToken;

            return response()->json([
                'status' => 'success',
                'message' => 'Admin login successful',
                'token' => $token,
                'user' => $user->load('admin')
            ]);

        } catch (\Exception $e) {
            Log::error('Admin login failed: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Login failed'
            ], 500);
        }
    }

    // Logout
    public function logout(Request $request)
    {
        try {
            if (!$request->user()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No authenticated user'
                ], 401);
            }

            // Revoke the current access token
$request->user()->tokens()->delete();
            // Alternative: Revoke all tokens for the user
            // $request->user()->tokens()->delete();

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

    // Get Users (Admin only)
    public function getUsers(Request $request)
    {
        try {
            if ($request->user()->role !== 'admin') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Forbidden'
                ], 403);
            }

            $users = User::with(['admin', 'teacher', 'student'])->get();
            
            return response()->json([
                'status' => 'success',
                'data' => $users
            ]);

        } catch (\Exception $e) {
            Log::error('Get users failed: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve users'
            ], 500);
        }
>>>>>>> 131317a (Initial commit of Read-and-Grow backend)
    }
}
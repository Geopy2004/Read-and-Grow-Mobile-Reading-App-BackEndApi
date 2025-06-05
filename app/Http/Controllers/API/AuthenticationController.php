<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class AuthenticationController extends Controller
{
    public function registerAdmin(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'username' => 'required|unique:users',
            'admin_email' => 'required|email|unique:users,admin_email',
            'admin_password' => 'required|min:6|confirmed',
            'admin_security_code' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

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
            'teacher_name' => 'required',
            'teacher_position' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

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
            'student_grade' => 'required',
            'student_section' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

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
            'username' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

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
    }
}
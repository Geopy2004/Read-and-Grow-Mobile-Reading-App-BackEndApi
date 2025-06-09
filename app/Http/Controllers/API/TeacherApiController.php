<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class TeacherApiController extends Controller
{
    // List all teachers
    public function index()
    {
        $teachers = Teacher::with('user')->get();
        return response()->json(['status' => 'success', 'data' => $teachers]);
    }

    // Show single teacher
    public function show($id)
    {
        $teacher = Teacher::with('user')->find($id);
        if (!$teacher) {
            return response()->json(['status' => 'error', 'message' => 'Teacher not found'], 404);
        }
        return response()->json(['status' => 'success', 'data' => $teacher]);
    }

    // Create a new teacher
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:users,username',
            'password' => 'required|min:6',
            'teacher_email' => 'required|email|unique:teachers,teacher_email',
            'teacher_name' => 'required|string',
            'teacher_position' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => 'Validation error', 'errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => 'teacher',
        ]);

        $teacher = $user->teacher()->create([
            'teacher_email' => $request->teacher_email,
            'teacher_name' => $request->teacher_name,
            'teacher_position' => $request->teacher_position,
        ]);

        return response()->json(['status' => 'success', 'message' => 'Teacher created', 'data' => $teacher], 201);
    }

    // Update teacher
    public function update(Request $request, $id)
    {
        $teacher = Teacher::find($id);
        if (!$teacher) {
            return response()->json(['status' => 'error', 'message' => 'Teacher not found'], 404);
        }

        $user = $teacher->user;

        $validator = Validator::make($request->all(), [
            'username' => 'sometimes|unique:users,username,' . $user->id,
            'password' => 'sometimes|min:6',
            'teacher_email' => 'sometimes|email|unique:teachers,teacher_email,' . $teacher->id,
            'teacher_name' => 'sometimes|string',
            'teacher_position' => 'sometimes|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => 'Validation error', 'errors' => $validator->errors()], 422);
        }

        if ($request->has('username')) {
            $user->username = $request->username;
        }
        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        if ($request->has('teacher_email')) {
            $teacher->teacher_email = $request->teacher_email;
        }
        if ($request->has('teacher_name')) {
            $teacher->teacher_name = $request->teacher_name;
        }
        if ($request->has('teacher_position')) {
            $teacher->teacher_position = $request->teacher_position;
        }
        $teacher->save();

        return response()->json(['status' => 'success', 'message' => 'Teacher updated', 'data' => $teacher]);
    }

    // Delete teacher
    public function destroy($id)
    {
        $teacher = Teacher::find($id);
        if (!$teacher) {
            return response()->json(['status' => 'error', 'message' => 'Teacher not found'], 404);
        }

        $teacher->user()->delete(); // this should cascade to delete teacher as well

        return response()->json(['status' => 'success', 'message' => 'Teacher deleted']);
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class StudentApiController extends Controller
{
    // List all students
    public function index()
    {
        $students = Student::with('user')->get();
        return response()->json(['status' => 'success', 'data' => $students]);
    }

    // Show single student
    public function show($id)
    {
        $student = Student::with('user')->find($id);
        if (!$student) {
            return response()->json(['status' => 'error', 'message' => 'Student not found'], 404);
        }
        return response()->json(['status' => 'success', 'data' => $student]);
    }

    // Create a new student
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:users,username',
            'password' => 'required|min:6',
            'student_name' => 'required|string',
            'student_lrn' => 'required|unique:students,student_lrn',
            'student_grade' => 'required|string',
            'student_section' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => 'Validation error', 'errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => 'student',
        ]);

        $student = $user->student()->create([
            'student_name' => $request->student_name,
            'student_lrn' => $request->student_lrn,
            'student_grade' => $request->student_grade,
            'student_section' => $request->student_section,
        ]);

        return response()->json(['status' => 'success', 'message' => 'Student created', 'data' => $student], 201);
    }

    // Update student
    public function update(Request $request, $id)
    {
        $student = Student::find($id);
        if (!$student) {
            return response()->json(['status' => 'error', 'message' => 'Student not found'], 404);
        }

        $user = $student->user;

        $validator = Validator::make($request->all(), [
            'username' => 'sometimes|unique:users,username,' . $user->id,
            'password' => 'sometimes|min:6',
            'student_name' => 'sometimes|string',
            'student_lrn' => 'sometimes|unique:students,student_lrn,' . $student->id,
            'student_grade' => 'sometimes|string',
            'student_section' => 'sometimes|string',
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

        if ($request->has('student_name')) {
            $student->student_name = $request->student_name;
        }
        if ($request->has('student_lrn')) {
            $student->student_lrn = $request->student_lrn;
        }
        if ($request->has('student_grade')) {
            $student->student_grade = $request->student_grade;
        }
        if ($request->has('student_section')) {
            $student->student_section = $request->student_section;
        }
        $student->save();

        return response()->json(['status' => 'success', 'message' => 'Student updated', 'data' => $student]);
    }

    // Delete student
    public function destroy($id)
    {
        $student = Student::find($id);
        if (!$student) {
            return response()->json(['status' => 'error', 'message' => 'Student not found'], 404);
        }

        $student->user()->delete();

        return response()->json(['status' => 'success', 'message' => 'Student deleted']);
    }
}

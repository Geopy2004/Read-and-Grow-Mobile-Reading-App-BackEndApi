<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminApiController extends Controller
{
    // List all admins
    public function index()
    {
        $admins = Admin::with('user')->get();
        return response()->json(['status' => 'success', 'data' => $admins]);
    }

    // Show one admin by ID
    public function show($id)
    {
        $admin = Admin::with('user')->find($id);
        if (!$admin) {
            return response()->json(['status' => 'error', 'message' => 'Admin not found'], 404);
        }
        return response()->json(['status' => 'success', 'data' => $admin]);
    }

    // Create a new admin
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:users,username',
            'admin_email' => 'required|email|unique:admins,admin_email',
            'password' => 'required|min:6',
            'admin_security_code' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => 'Validation error', 'errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => 'admin',
        ]);

        $admin = $user->admin()->create([
            'admin_email' => $request->admin_email,
            'admin_security_code' => Hash::make($request->admin_security_code),
        ]);

        return response()->json(['status' => 'success', 'message' => 'Admin created', 'data' => $admin], 201);
    }

    // Update an existing admin
    public function update(Request $request, $id)
    {
        $admin = Admin::find($id);
        if (!$admin) {
            return response()->json(['status' => 'error', 'message' => 'Admin not found'], 404);
        }

        $user = $admin->user;

        $validator = Validator::make($request->all(), [
            'username' => 'sometimes|unique:users,username,' . $user->id,
            'admin_email' => 'sometimes|email|unique:admins,admin_email,' . $admin->id,
            'password' => 'sometimes|min:6',
            'admin_security_code' => 'sometimes',
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

        if ($request->has('admin_email')) {
            $admin->admin_email = $request->admin_email;
        }

        if ($request->has('admin_security_code')) {
            $admin->admin_security_code = Hash::make($request->admin_security_code);
        }

        $admin->save();

        return response()->json(['status' => 'success', 'message' => 'Admin updated', 'data' => $admin]);
    }

    // Delete an admin
    public function destroy($id)
    {
        $admin = Admin::find($id);
        if (!$admin) {
            return response()->json(['status' => 'error', 'message' => 'Admin not found'], 404);
        }

        $admin->user()->delete(); // this will also delete admin record because of cascade
        // or just $admin->delete(); if you handle it separately

        return response()->json(['status' => 'success', 'message' => 'Admin deleted']);
    }
}

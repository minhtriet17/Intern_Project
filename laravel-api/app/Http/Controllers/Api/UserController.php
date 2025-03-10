<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controller; // Thêm dòng này

class UserController extends Controller
{
    // Lấy danh sách người dùng
    public function getUsers()
    {
        try {
            $users = User::all();
            return response()->json($users);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch users'], 500);
        }
    }

    // Thêm người dùng mới
    public function store(Request $request)
    {
        // Validate dữ liệu nhập vào
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required|string|in:admin,user',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::addUser($request->name, $request->email, $request->role);
        return response()->json($user, 201);
    }

    // Cập nhật thông tin người dùng
    public function update(Request $request, $id)
    {
        // Validate dữ liệu nhập vào
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'role' => 'required|string|in:admin,user',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try {
            \Log::info('Updating user', ['id' => $id, 'data' => $request->all()]);
            $user = User::updateUser($id, $request->name, $request->email, $request->role);
            \Log::info('User updated successfully', ['user' => $user]);
            return response()->json($user);
        } catch (\Exception $e) {
            \Log::error('Failed to update user', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to update user'], 500);
        }
    }
    // Xóa người dùng
    public function destroy($id)
    {
        $user = User::deleteUser($id);
        return response()->json(['message' => 'User deleted successfully!']);
    }
}
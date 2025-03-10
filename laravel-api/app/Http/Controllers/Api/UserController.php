<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controller; // Thêm dòng này

class UserController extends Controller
{
    // Lấy danh sách người dùng
    public function index()
    {
        $users = User::getAllUsers();
        return response()->json($users);
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

        $user = User::updateUser($id, $request->name, $request->email, $request->role);
        return response()->json($user);
    }

    // Xóa người dùng
    public function destroy($id)
    {
        $user = User::deleteUser($id);
        return response()->json(['message' => 'User deleted successfully!']);
    }
}
<?php

use App\Http\Controllers\Api\LectureController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\UserController;

use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;

Route::apiResource('lectures', LectureController::class);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api'); // Dùng cho JWT

Route::middleware('auth:api')->get('/users', [UserController::class, 'index']);

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'check']);

Route::get('lectures', [LectureController::class, 'index']);
Route::post('lectures', [LectureController::class, 'store']);
Route::get('lectures/{id}', [LectureController::class, 'show']);
Route::get('lectures/{id}/edit', [LectureController::class, 'edit']);
Route::put('lectures/{id}/edit', [LectureController::class, 'update']);
Route::delete('lectures/{id}/delete', [LectureController::class,'destroy']);

// Lấy tất cả người dùng
Route::get('/users', [UserController::class, 'getUsers']);

// Thêm người dùng
Route::post('/users', [UserController::class, 'store']);

// Sửa người dùng
Route::put('/users/{id}', [UserController::class, 'update']);

// Xóa người dùng
Route::delete('/users/{id}', [UserController::class, 'destroy']);

Route::middleware('auth')->group(function () {
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
});

Route::middleware('role:admin')->get('/admin', function () {
    return view('admin.index');
});
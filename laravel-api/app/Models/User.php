<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject; 

class User extends Authenticatable implements JWTSubject // Implement JWTSubject
{
    use HasFactory, Notifiable;

    // Định nghĩa các trường có thể được gán (mass assignable)
    protected $fillable = [
        'name',
        'first_name', // Thêm first_name
        'last_name',  // Thêm last_name
        'email',
        'password',
        'role', // Thêm role để phù hợp với phân quyền
    ];

    // Các trường không nên hiển thị khi chuyển đổi sang dạng JSON
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Các kiểu dữ liệu đặc biệt
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Phương thức để lấy khóa của người dùng (JWT)
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    // Phương thức để lấy các claims của JWT (nếu cần)
    public function getJWTCustomClaims()
    {
        return [];
    }

    // Các phương thức CRUD sử dụng Eloquent ORM thay cho PDO
    public static function getAllUsers() {
        return self::all(); // Lấy tất cả người dùng
    }

    public static function addUser($name, $email, $role) {
        return self::create([
            'name' => $name,
            'email' => $email,
            'role' => $role,
            'password' => bcrypt('defaultpassword') // Có thể thêm mật khẩu mặc định hoặc yêu cầu nhập
        ]);
    }

    public static function updateUser($id, $name, $email, $role) {
        $user = self::findOrFail($id);
        $user->update([
            'name' => $name,
            'email' => $email,
            'role' => $role,
        ]);
        return $user;
    }

    public static function deleteUser($id) {
        $user = self::findOrFail($id);
        return $user->delete();
    }
}

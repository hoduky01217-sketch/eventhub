<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Các trường cho phép gán dữ liệu hàng loạt
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    // Các trường ẩn khi trả dữ liệu
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Chuyển đổi kiểu dữ liệu
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Kiểm tra tài khoản có phải Admin không
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // Các sự kiện do tài khoản tạo
    public function createdEvents(): HasMany
    {
        return $this->hasMany(Event::class, 'created_by');
    }

    // Các lượt đăng ký sự kiện của người dùng
    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    // Các lượt check-in do Admin thực hiện
    public function checkedInRegistrations(): HasMany
    {
        return $this->hasMany(
            Registration::class,
            'checked_in_by'
        );
    }
}

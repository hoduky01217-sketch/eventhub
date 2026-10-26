<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'description',
        'location',
        'cover_image',
        'starts_at',
        'ends_at',
        'registration_deadline',
        'capacity',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'registration_deadline' => 'datetime',
            'capacity' => 'integer',
        ];
    }

    // Chuyên mục chứa sự kiện
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // Admin tạo sự kiện
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Danh sách đăng ký
    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }
}
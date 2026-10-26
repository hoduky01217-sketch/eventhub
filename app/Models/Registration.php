<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Registration extends Model
{
    protected $fillable = [
        'event_id',
        'user_id',
        'status',
        'qr_token',
        'registered_at',
    ];

    protected $hidden = [
        'qr_token',
    ];

    protected function casts(): array
    {
        return [
            'registered_at' => 'datetime',
            'cancelled_at' => 'datetime',
            'checked_in_at' => 'datetime',
        ];
    }

    // Sự kiện được đăng ký
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    // Người đăng ký
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Admin xác nhận check-in
    public function checkedInBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checked_in_by');
    }
}
<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class RegistrationService
{
    // Đăng ký tham gia sự kiện
    public function register(Event $event, User $user): void
    {
        if ($user->role !== 'user') {
            abort(403, 'Chỉ tài khoản User được đăng ký sự kiện.');
        }

        DB::transaction(function () use ($event, $user) {

            // Khóa sự kiện để xử lý tuần tự các lượt đăng ký
            $event = Event::whereKey($event->id)
                ->lockForUpdate()
                ->firstOrFail();

            if (
                $event->status !== 'published' ||
                !$event->category()->where('is_active', true)->exists()
            ) {
                throw ValidationException::withMessages([
                    'registration' => 'Sự kiện không còn mở đăng ký.'
                ]);
            }

            // Kiểm tra hạn đăng ký và thời gian bắt đầu
            if (
                now()->greaterThan($event->registration_deadline) ||
                now()->greaterThanOrEqualTo($event->starts_at)
            ) {
                throw ValidationException::withMessages([
                    'registration' => 'Sự kiện đã hết hạn đăng ký.'
                ]);
            }

            // Tìm đăng ký trước đó của User
            $registration = Registration::where('event_id', $event->id)
                ->where('user_id', $user->id)
                ->first();

            if ($registration && $registration->status === 'registered') {
                throw ValidationException::withMessages([
                    'registration' => 'Bạn đã đăng ký sự kiện này!'
                ]);
            }

            // Đếm lượt đăng ký còn hiệu lực
            $registeredCount = Registration::where('event_id', $event->id)
                ->where('status', 'registered')
                ->count();

            if ($registeredCount >= $event->capacity) {
                throw ValidationException::withMessages([
                    'registration' => 'Sự kiện đã đủ số lượng người tham gia.'
                ]);
            }

            // Tạo mã vé ngẫu nhiên cho giai đoạn QR Code
            $token = Str::random(64);

            if ($registration) {
                // Đăng ký lại sau khi hủy
                $registration->status = 'registered';
                $registration->qr_token = $token;
                $registration->registered_at = now();
                $registration->cancelled_at = null;
                $registration->checked_in_at = null;
                $registration->checked_in_by = null;
                $registration->save();
            } else {
                // Đăng ký lần đầu
                Registration::create([
                    'event_id' => $event->id,
                    'user_id' => $user->id,
                    'status' => 'registered',
                    'qr_token' => $token,
                    'registered_at' => now(),
                ]);
            }

        }, 3);
    }

    // Hủy đăng ký tham gia
    public function cancel(Registration $registration, User $user): void
    {
        if ($user->role !== 'user' ||
            $registration->user_id !== $user->id) {
            abort(403, 'Bạn không có quyền hủy đăng ký này.');
        }

        DB::transaction(function () use ($registration, $user) {

            // Khóa sự kiện trước để đồng bộ với thao tác đăng ký
            $event = Event::whereKey($registration->event_id)
                ->lockForUpdate()
                ->firstOrFail();

            $registration = Registration::whereKey($registration->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($registration->user_id !== $user->id) {
                abort(403);
            }

            if ($registration->status !== 'registered') {
                throw ValidationException::withMessages([
                    'registration' => 'Đăng ký này đã được hủy trước đó.'
                ]);
            }

            if ($registration->checked_in_at !== null) {
                throw ValidationException::withMessages([
                    'registration' => 'Bạn đã check-in nên không thể hủy.'
                ]);
            }

            if (now()->greaterThanOrEqualTo($event->starts_at)) {
                throw ValidationException::withMessages([
                    'registration' => 'Sự kiện đã bắt đầu, không thể hủy.'
                ]);
            }

            $registration->status = 'cancelled';
            $registration->cancelled_at = now();
            $registration->save();

        }, 3);
    }
}

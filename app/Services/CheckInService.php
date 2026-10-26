<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CheckInService
{
    public function checkIn(string $token, User $admin): Registration
    {
        if (!$admin->isAdmin()) {
            abort(403, 'Chỉ Admin được check-in.');
        }

        // Mã vé phải có đúng định dạng
        if (!preg_match('/\A[A-Za-z0-9]{64}\z/D', $token)) {
            throw ValidationException::withMessages([
                'token' => 'Mã QR không đúng định dạng.',
            ]);
        }

        return DB::transaction(function () use ($token, $admin) {

            // Tìm đăng ký theo mã QR
            $candidate = Registration::where('qr_token', $token)
                ->first();

            if (!$candidate) {
                throw ValidationException::withMessages([
                    'token' => 'Không tìm thấy vé trong hệ thống.',
                ]);
            }

            // Khóa sự kiện trước, đồng bộ với RegistrationService
            $event = Event::whereKey($candidate->event_id)
                ->lockForUpdate()
                ->firstOrFail();

            // Khóa đăng ký để tránh hai Admin check-in đồng thời
            $registration = Registration::whereKey($candidate->id)
                ->lockForUpdate()
                ->firstOrFail();

            // Kiểm tra lại token sau khi lấy khóa
            if (!hash_equals($registration->qr_token, $token)) {
                throw ValidationException::withMessages([
                    'token' => 'Mã vé đã thay đổi hoặc không còn hợp lệ.',
                ]);
            }

            // Vé đã bị hủy
            if ($registration->status !== 'registered') {
                throw ValidationException::withMessages([
                    'token' => 'Vé này đã bị hủy.',
                ]);
            }

            // Sự kiện phải còn hiệu lực
            if ($event->status !== 'published') {
                throw ValidationException::withMessages([
                    'token' => 'Sự kiện chưa công bố hoặc đã bị hủy.',
                ]);
            }

            // Kiểm tra vé đã sử dụng chưa
            if ($registration->checked_in_at !== null) {
                throw ValidationException::withMessages([
                    'token' => 'Vé đã được check-in lúc ' .
                        $registration->checked_in_at->format('d/m/Y H:i:s'),
                ]);
            }

            // Cho phép check-in từ 60 phút trước khi sự kiện bắt đầu
            // đến thời gian kết thúc sự kiện
            $checkInOpensAt = $event->starts_at->copy()->subHour();

            if (now()->lt($checkInOpensAt)) {
                throw ValidationException::withMessages([
                    'token' => 'Chưa đến thời gian check-in.',
                ]);
            }

            if (now()->gt($event->ends_at)) {
                throw ValidationException::withMessages([
                    'token' => 'Sự kiện đã kết thúc thời gian check-in.',
                ]);
            }

            // Cập nhật thông tin check-in
            $registration->checked_in_at = now();
            $registration->checked_in_by = $admin->id;
            $registration->save();

            return $registration->load(['user', 'event']);

        }, 3);
    }
}

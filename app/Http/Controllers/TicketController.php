<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Services\QrCodeService;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function show(
        Request $request,
        Registration $registration,
        QrCodeService $qrCodeService
    ) {
        // Chỉ chủ sở hữu vé mới được xem
        abort_unless(
            $request->user()->role === 'user' &&
            (int) $registration->user_id ===
            (int) $request->user()->id,
            403,
            'Bạn không có quyền xem vé này.'
        );

        // Vé bị hủy không thể sử dụng
        if ($registration->status !== 'registered') {
            return redirect()
                ->route('registrations.index')
                ->with('error', 'Vé đã bị hủy hoặc không còn hợp lệ.');
        }

        $registration->load([
            'event',
            'event.category'
        ]);

        // Tạo ảnh QR Code từ token
        $qrImage = $qrCodeService->generate(
            $registration->qr_token
        );

        return view('registrations.ticket', compact(
            'registration',
            'qrImage'
        ));
    }
}

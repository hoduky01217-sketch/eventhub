<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use App\Services\RegistrationService;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    // Hiển thị các sự kiện User đã đăng ký
    public function index(Request $request)
    {
        abort_if($request->user()->role !== 'user', 403);

        $registrations = Registration::with([
            'event',
            'event.category'
        ])
        ->where('user_id', $request->user()->id)
        ->orderByDesc('registered_at')
        ->paginate(10);

        return view(
            'registrations.index',
            compact('registrations')
        );
    }

    // Xử lý đăng ký tham gia
    public function store(
        Request $request,
        Event $event,
        RegistrationService $service
    ) {
        abort_if($request->user()->role !== 'user', 403);

        $service->register($event, $request->user());

        return redirect()
            ->route('registrations.index')
            ->with('success', 'Đăng ký tham gia sự kiện thành công!');
    }

    // Xử lý hủy đăng ký
    public function destroy(
        Request $request,
        Registration $registration,
        RegistrationService $service
    ) {
        abort_if($request->user()->role !== 'user', 403);

        $service->cancel($registration, $request->user());

        return redirect()
            ->route('registrations.index')
            ->with('success', 'Hủy đăng ký thành công!');
    }
}

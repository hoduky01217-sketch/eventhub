<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Services\CheckInService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CheckInController extends Controller
{
    // Trang quét QR dành cho Admin
    public function index()
    {
        $checkedInCount = Registration::where('status', 'registered')
            ->whereNotNull('checked_in_at')
            ->count();

        $recentCheckIns = Registration::with(['user', 'event'])
            ->whereNotNull('checked_in_at')
            ->orderByDesc('checked_in_at')
            ->limit(10)
            ->get();

        return view('admin.checkin.index', compact(
            'checkedInCount',
            'recentCheckIns'
        ));
    }

    // Xác nhận check-in
    public function store(
        Request $request,
        CheckInService $service
    ): JsonResponse {
        $data = $request->validate([
            'token' => ['required', 'string', 'size:64'],
        ]);

        $registration = $service->checkIn(
            $data['token'],
            $request->user()
        );

        return response()->json([
            'success' => true,
            'message' => 'Check-in thành công!',
            'participant' => $registration->user->name,
            'event' => $registration->event->title,
            'checked_in_at' => $registration
                ->checked_in_at
                ->format('d/m/Y H:i:s'),
        ]);
    }
}

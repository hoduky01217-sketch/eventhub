<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function index(Request $request, Event $event)
    {
        $query = $event->registrations()
            ->with('user');

        // Lọc theo trạng thái
        $status = $request->query('status');

        if (in_array($status, ['registered', 'cancelled'], true)) {
            $query->where('status', $status);
        }

        $registrations = $query
            ->orderByDesc('registered_at')
            ->paginate(15)
            ->withQueryString();

        // Thống kê riêng của sự kiện
        $summary = [
            'registered' => $event->registrations()
                ->where('status', 'registered')
                ->count(),

            'checkedIn' => $event->registrations()
                ->where('status', 'registered')
                ->whereNotNull('checked_in_at')
                ->count(),

            'cancelled' => $event->registrations()
                ->where('status', 'cancelled')
                ->count(),
        ];

        return view('admin.registrations.index', compact(
            'event',
            'registrations',
            'summary'
        ));
    }
}



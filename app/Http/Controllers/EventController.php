<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;
use App\Models\Registration;
use Illuminate\Http\Request;

class EventController extends Controller
{
    // Trang chủ: danh sách sự kiện
    public function index(Request $request)
    {
        $now = now();

        $categories = Category::where('is_active', true)
            ->orderBy('name')
            ->get();

        $query = Event::with('category')
            ->withCount([
                'registrations as registered_count' => function ($q) {
                    $q->where('status', 'registered');
                }
            ])
            ->where('status', 'published')
            ->whereHas('category', function ($q) {
                $q->where('is_active', true);
            });

        // Tìm kiếm theo tên hoặc địa điểm
        if ($request->filled('q')) {
            $keyword = $request->input('q');

            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                  ->orWhere('location', 'like', "%{$keyword}%");
            });
        }

        // Lọc theo chuyên mục
        if ($request->filled('category_id')) {
            $query->where(
                'category_id',
                $request->input('category_id')
            );
        }

        // Lọc theo thời gian
        $period = $request->input('period', 'available');

        if ($period === 'available') {
            $query->where('ends_at', '>=', $now);
        } elseif ($period === 'upcoming') {
            $query->where('starts_at', '>', $now);
        } elseif ($period === 'ended') {
            $query->where('ends_at', '<', $now);
        }

        $events = $query
            ->orderBy('starts_at')
            ->paginate(9)
            ->withQueryString();

        return view('events.index', compact(
            'events',
            'categories',
            'period'
        ));
    }

    // Hiển thị chi tiết sự kiện
    public function show(Event $event)
    {
        // Chỉ hiển thị sự kiện đã công bố
        abort_unless(
            $event->status === 'published' &&
            $event->category()->where('is_active', true)->exists(),
            404
        );

        $event->load('category');

        $event->loadCount([
            'registrations as registered_count' => function ($q) {
                $q->where('status', 'registered');
            }
        ]);

        $registration = null;

        if (auth()->check()) {
            $registration = Registration::where(
                'user_id',
                auth()->id()
            )
            ->where('event_id', $event->id)
            ->first();
        }

        $remaining = max(
            0,
            $event->capacity - $event->registered_count
        );

        $canRegister =
            auth()->check() &&
            auth()->user()->role === 'user' &&
            $event->starts_at->isFuture() &&
            now()->lessThanOrEqualTo($event->registration_deadline) &&
            $remaining > 0 &&
            (!$registration || $registration->status === 'cancelled');

        return view('events.show', compact(
            'event',
            'registration',
            'remaining',
            'canRegister'
        ));
    }
}

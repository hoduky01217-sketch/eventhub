<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Event;
use App\Models\Registration;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $now = now();

        // 1. Các chỉ số tổng quan
        $stats = [
            'totalEvents' => Event::count(),

            'upcomingEvents' => Event::where('status', 'published')
                ->where('starts_at', '>', $now)
                ->count(),

            'totalUsers' => User::where('role', 'user')->count(),

            'totalCategories' => Category::count(),

            'activeRegistrations' => Registration::where(
                'status', 'registered'
            )->count(),

            'checkedIn' => Registration::where(
                'status', 'registered'
            )->whereNotNull('checked_in_at')->count(),
        ];

        // Tỷ lệ check-in
        $stats['checkInRate'] = $stats['activeRegistrations'] > 0
            ? round(
                $stats['checkedIn'] * 100 /
                $stats['activeRegistrations'],
                1
            )
            : 0;

        // 2. Số lượng sự kiện theo trạng thái
        $eventStatuses = Event::selectRaw(
                'status, COUNT(*) as total'
            )
            ->groupBy('status')
            ->pluck('total', 'status');

        // 3. Thống kê đăng ký còn hiệu lực trong 12 tháng
        $firstMonth = now()->startOfMonth()->subMonths(11);

        $monthlyRows = Registration::selectRaw(
                'YEAR(registered_at) as y,
                 MONTH(registered_at) as m,
                 COUNT(*) as total'
            )
            ->where('status', 'registered')
            ->where('registered_at', '>=', $firstMonth)
            ->groupByRaw(
                'YEAR(registered_at), MONTH(registered_at)'
            )
            ->get();

        $monthlyMap = $monthlyRows->mapWithKeys(
            function ($row) {
                $key = sprintf('%04d-%02d', $row->y, $row->m);

                return [$key => (int) $row->total];
            }
        );

        $months = [];
        $registrationCounts = [];

        for ($i = 0; $i < 12; $i++) {
            $month = $firstMonth->copy()->addMonths($i);
            $key = $month->format('Y-m');

            $months[] = $month->format('m/Y');
            $registrationCounts[] = $monthlyMap->get($key, 0);
        }

        // 4. Dữ liệu truyền cho Chart.js
        $chartData = [
            'months' => $months,

            'registrationCounts' => $registrationCounts,

            'eventStatusLabels' => [
                'Đã công bố',
                'Bản nháp',
                'Đã hủy',
            ],

            'eventStatusCounts' => [
                (int) ($eventStatuses['published'] ?? 0),
                (int) ($eventStatuses['draft'] ?? 0),
                (int) ($eventStatuses['cancelled'] ?? 0),
            ],
        ];

        // 5. Top 5 sự kiện có nhiều người đăng ký
        $topEvents = Event::withCount([
                'registrations as active_registrations_count'
                    => function ($query) {
                        $query->where('status', 'registered');
                    },
            ])
            ->orderByDesc('active_registrations_count')
            ->orderBy('id')
            ->limit(5)
            ->get();

        // 6. Tám lượt đăng ký gần đây
        $recentRegistrations = Registration::with([
                'user:id,name',
                'event:id,title,slug',
            ])
            ->orderByDesc('registered_at')
            ->limit(8)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'chartData',
            'topEvents',
            'recentRegistrations'
        ));
    }
}

<x-app-layout>
    <div class="p-6 lg:p-8 space-y-8 max-w-7xl mx-auto w-full">

        <!-- Header Info Row -->
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">
                    Tổng quan hệ thống
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Theo dõi hoạt động, lưu lượng và thống kê vận hành EventHub
                </p>
            </div>

            <div class="flex items-center gap-2 bg-white px-3.5 py-1.5 rounded-xl border border-slate-200/80 text-xs font-semibold text-slate-600 shadow-2xs">
                <x-icons.clock class="w-4 h-4 text-blue-600" />
                <span>{{ now()->format('d/m/Y H:i') }}</span>
            </div>
        </div>

        <!-- ================= WHITE GREETING BANNER (PHƯƠNG ÁN 1) ================= -->
        <div class="bg-white rounded-3xl p-6 lg:p-8 border border-slate-200/80 shadow-xs flex flex-col md:flex-row items-start md:items-center justify-between gap-6 relative overflow-hidden">
            <div class="relative z-10 space-y-2">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-100">
                    <x-icons.bolt class="w-3.5 h-3.5 text-blue-600" />
                    <span>Trung tâm điều hành EventHub</span>
                </div>

                <h2 class="text-2xl lg:text-3xl font-black tracking-tight text-slate-900">
                    Chào mừng trở lại, {{ Auth::user()->name }}!
                </h2>

                <p class="text-xs lg:text-sm text-slate-500 max-w-xl leading-relaxed">
                    Hệ thống đang hoạt động tối ưu. Hiện có <strong class="text-slate-800 font-semibold">{{ $stats['upcomingEvents'] }} sự kiện</strong> đã công bố và sẵn sàng tiếp nhận lượt đăng ký.
                </p>
            </div>

            <div class="relative z-10 flex flex-wrap items-center gap-3">
                <a href="{{ route('admin.checkin.index') }}"
                   class="px-4 py-2.5 rounded-xl text-xs font-bold bg-blue-600 text-white hover:bg-blue-700 shadow-sm shadow-blue-500/20 transition inline-flex items-center gap-2">
                    <x-icons.qr-code class="w-4 h-4 text-white" />
                    <span>Quét QR Check-in</span>
                </a>

                <a href="{{ route('admin.events.index') }}"
                   class="px-4 py-2.5 rounded-xl text-xs font-semibold bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200/80 transition inline-flex items-center gap-2">
                    <x-icons.calendar class="w-4 h-4 text-slate-500" />
                    <span>Quản lý sự kiện</span>
                </a>
            </div>
        </div>

        <!-- ================= 6 BENTO KPI CARDS (NO UGLY NUMBERS, PURE SVG) ================= -->
        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4">
            <!-- Card 1: Tổng sự kiện -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center mb-3">
                    <x-icons.calendar class="w-5 h-5 text-blue-600" />
                </div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Tổng sự kiện</p>
                <p class="text-2xl font-black text-slate-900 mt-1">{{ $stats['totalEvents'] }}</p>
                <p class="text-[11px] text-slate-400 mt-1">Tất cả trạng thái</p>
            </div>

            <!-- Card 2: Sắp diễn ra -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-3">
                    <x-icons.clock class="w-5 h-5 text-emerald-600" />
                </div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Sắp diễn ra</p>
                <p class="text-2xl font-black text-slate-900 mt-1">{{ $stats['upcomingEvents'] }}</p>
                <span class="inline-block text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full mt-1">Đã công bố</span>
            </div>

            <!-- Card 3: Người dùng -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center mb-3">
                    <x-icons.users class="w-5 h-5 text-blue-600" />
                </div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Người dùng</p>
                <p class="text-2xl font-black text-slate-900 mt-1">{{ $stats['totalUsers'] }}</p>
                <p class="text-[11px] text-slate-400 mt-1">Tài khoản User</p>
            </div>

            <!-- Card 4: Lượt đăng ký -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition">
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center mb-3">
                    <x-icons.ticket class="w-5 h-5 text-amber-600" />
                </div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Lượt đăng ký</p>
                <p class="text-2xl font-black text-slate-900 mt-1">{{ $stats['activeRegistrations'] }}</p>
                <p class="text-[11px] text-slate-400 mt-1">Đăng ký còn hiệu lực</p>
            </div>

            <!-- Card 5: Đã check-in -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition">
                <div class="w-10 h-10 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center mb-3">
                    <x-icons.check class="w-5 h-5 text-sky-600" />
                </div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Đã check-in</p>
                <p class="text-2xl font-black text-slate-900 mt-1">{{ $stats['checkedIn'] }}</p>
                <p class="text-[11px] text-slate-400 mt-1">Lượt xác nhận</p>
            </div>

            <!-- Card 6: Tỷ lệ check-in -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition">
                <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center mb-3">
                    <x-icons.trending-up class="w-5 h-5 text-rose-600" />
                </div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Tỷ lệ check-in</p>
                <p class="text-2xl font-black text-slate-900 mt-1">{{ $stats['checkInRate'] }}%</p>
                <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden mt-1.5">
                    <div class="bg-blue-600 h-full rounded-full" style="width: {{ min(100, max(0, (float)$stats['checkInRate'])) }}%"></div>
                </div>
            </div>
        </div>

        <!-- ================= CHARTS ROW ================= -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Biểu đồ đăng ký theo tháng (2 Cột) -->
            <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-200/80 p-6 shadow-xs">
                <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                    <div>
                        <h3 class="font-extrabold text-base text-slate-900">
                            Xu hướng đăng ký theo tháng
                        </h3>
                        <p class="text-xs text-slate-400 mt-0.5">
                            Số lượt đăng ký còn hiệu lực trong 12 tháng gần nhất
                        </p>
                    </div>
                    <span class="text-xs font-bold text-blue-600 bg-blue-50 px-3 py-1 rounded-full">
                        12 Tháng qua
                    </span>
                </div>

                <div class="relative h-72 w-full">
                    <canvas id="registrationTrendChart"></canvas>
                </div>
            </div>

            <!-- Biểu đồ trạng thái sự kiện (1 Cột) -->
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-xs flex flex-col justify-between">
                <div>
                    <h3 class="font-extrabold text-base text-slate-900 mb-0.5">
                        Trạng thái sự kiện
                    </h3>
                    <p class="text-xs text-slate-400 mb-4">
                        Phân bố sự kiện trên toàn hệ thống
                    </p>

                    @if($stats['totalEvents'] > 0)
                        <div class="relative h-56 w-full flex items-center justify-center">
                            <canvas id="eventStatusChart"></canvas>
                        </div>
                    @else
                        <div class="h-56 flex items-center justify-center text-slate-400 text-xs">
                            Chưa có dữ liệu sự kiện
                        </div>
                    @endif
                </div>

                <div class="pt-4 border-t border-slate-100 grid grid-cols-3 gap-2 text-center text-xs">
                    <div>
                        <span class="block font-black text-emerald-600">{{ $stats['upcomingEvents'] }}</span>
                        <span class="text-[10px] text-slate-400">Đã công bố</span>
                    </div>
                    <div>
                        <span class="block font-black text-amber-500">{{ max(0, $stats['totalEvents'] - $stats['upcomingEvents']) }}</span>
                        <span class="text-[10px] text-slate-400">Bản nháp</span>
                    </div>
                    <div>
                        <span class="block font-black text-rose-500">0</span>
                        <span class="text-[10px] text-slate-400">Đã hủy</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= TABLES ROW ================= -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Top 5 sự kiện -->
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-xs">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-extrabold text-base text-slate-900">
                        Top 5 sự kiện nhiều lượt đăng ký
                    </h3>
                    <a href="{{ route('admin.events.index') }}" class="text-xs font-bold text-blue-600 hover:underline">
                        Xem tất cả &rarr;
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-xs">
                        <thead>
                            <tr class="text-slate-400 border-b border-slate-100 text-left">
                                <th class="pb-3 font-bold">Sự kiện</th>
                                <th class="pb-3 font-bold">Trạng thái</th>
                                <th class="pb-3 font-bold">Đăng ký</th>
                                <th class="pb-3 font-bold text-right">Lấp đầy</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium">
                            @forelse($topEvents as $event)
                                @php
                                    $percent = $event->capacity > 0
                                        ? min(100, round($event->active_registrations_count * 100 / $event->capacity))
                                        : 0;
                                @endphp
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="py-3.5 pr-2">
                                        <p class="font-bold text-slate-800 text-sm">
                                            {{ $event->title }}
                                        </p>
                                        <p class="text-[11px] text-slate-400 mt-0.5">
                                            {{ $event->starts_at->format('d/m/Y H:i') }}
                                        </p>
                                    </td>

                                    <td class="py-3.5 whitespace-nowrap">
                                        @if($event->status === 'published')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                Đã công bố
                                            </span>
                                        @elseif($event->status === 'cancelled')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                Đã hủy
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                Bản nháp
                                            </span>
                                        @endif
                                    </td>

                                    <td class="py-3.5 whitespace-nowrap font-bold text-slate-700">
                                        {{ $event->active_registrations_count }} / {{ $event->capacity }}
                                    </td>

                                    <td class="py-3.5 text-right min-w-28">
                                        <span class="font-bold text-blue-600">{{ $percent }}%</span>
                                        <div class="w-20 bg-slate-100 h-1.5 rounded-full ml-auto mt-1 overflow-hidden">
                                            <div class="bg-blue-600 h-full rounded-full" style="width: {{ $percent }}%"></div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-8 text-slate-400">
                                        Chưa có sự kiện nào.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Đăng ký gần đây -->
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-xs">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-extrabold text-base text-slate-900">
                        Danh sách đăng ký gần đây
                    </h3>
                    <span class="text-xs font-semibold text-slate-400">Thời gian thực</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-xs">
                        <thead>
                            <tr class="text-slate-400 border-b border-slate-100 text-left">
                                <th class="pb-3 font-bold">Người tham gia</th>
                                <th class="pb-3 font-bold">Sự kiện</th>
                                <th class="pb-3 font-bold">Thời gian</th>
                                <th class="pb-3 font-bold text-right">Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium">
                            @forelse($recentRegistrations as $registration)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="py-3.5 flex items-center gap-2.5">
                                        <div class="w-7 h-7 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-xs shrink-0">
                                            {{ strtoupper(substr($registration->user->name, 0, 1)) }}
                                        </div>
                                        <span class="font-bold text-slate-800">{{ $registration->user->name }}</span>
                                    </td>

                                    <td class="py-3.5 text-slate-600 truncate max-w-44">
                                        {{ $registration->event->title }}
                                    </td>

                                    <td class="py-3.5 text-slate-400 whitespace-nowrap">
                                        {{ $registration->registered_at->format('d/m/Y H:i') }}
                                    </td>

                                    <td class="py-3.5 text-right whitespace-nowrap">
                                        @if($registration->status === 'cancelled')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-100">
                                                Đã hủy
                                            </span>
                                        @elseif($registration->checked_in_at)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-100">
                                                Đã check-in
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                                Đã đăng ký
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-8 text-center text-slate-400">
                                        Chưa có lượt đăng ký nào.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ================= QUICK ACCESS ACTIONS ================= -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <a href="{{ route('admin.categories.index') }}"
               class="bg-white border border-slate-200/80 rounded-2xl p-5 hover:border-blue-400 hover:shadow-md transition flex items-center gap-4 group">
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 group-hover:scale-105 transition">
                    <x-icons.tag class="w-6 h-6 text-blue-600" />
                </div>
                <div>
                    <h4 class="font-bold text-slate-900 group-hover:text-blue-600 transition">Quản lý chuyên mục</h4>
                    <p class="text-xs text-slate-400 mt-0.5">{{ $stats['totalCategories'] }} chuyên mục hiện có</p>
                </div>
            </a>

            <a href="{{ route('admin.events.index') }}"
               class="bg-white border border-slate-200/80 rounded-2xl p-5 hover:border-blue-400 hover:shadow-md transition flex items-center gap-4 group">
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 group-hover:scale-105 transition">
                    <x-icons.calendar class="w-6 h-6 text-blue-600" />
                </div>
                <div>
                    <h4 class="font-bold text-slate-900 group-hover:text-blue-600 transition">Quản lý sự kiện</h4>
                    <p class="text-xs text-slate-400 mt-0.5">Xem, tạo và chỉnh sửa sự kiện</p>
                </div>
            </a>

            <a href="{{ route('admin.checkin.index') }}"
               class="bg-white border border-slate-200/80 rounded-2xl p-5 hover:border-blue-400 hover:shadow-md transition flex items-center gap-4 group">
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 group-hover:scale-105 transition">
                    <x-icons.qr-code class="w-6 h-6 text-blue-600" />
                </div>
                <div>
                    <h4 class="font-bold text-slate-900 group-hover:text-blue-600 transition">Check-in QR Code</h4>
                    <p class="text-xs text-slate-400 mt-0.5">Quét vé xác nhận tham gia</p>
                </div>
            </a>
        </div>

    </div>

    <!-- Pass chart data from Laravel to frontend -->
    <script>
        window.eventHubDashboardChartData = {{ Illuminate\Support\Js::from($chartData) }};
    </script>

    @vite('resources/js/admin-dashboard.js')
</x-app-layout>

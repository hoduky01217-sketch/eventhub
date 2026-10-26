@extends('layouts.public')

@section('title', 'Khám phá sự kiện - EventHub')

@section('content')

<!-- Hero Section (Luma Modern Spotlight - Blue Theme) -->
<section class="relative overflow-hidden bg-gradient-to-b from-blue-50/50 via-white to-slate-50 pt-16 pb-12 sm:pt-20 sm:pb-16 border-b border-slate-100">
    <!-- Subtle Background Ambient Glow -->
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[350px] bg-gradient-to-tr from-blue-200/40 via-sky-200/30 to-transparent blur-3xl -z-10 pointer-events-none rounded-full"></div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">

        <!-- Top Pill Badge -->
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold bg-white text-blue-700 border border-blue-100 shadow-sm mb-6 hover:border-blue-200 transition">
            <x-icons.sparkles class="w-3.5 h-3.5 text-blue-500" />
            <span>Nền tảng sự kiện thế hệ mới</span>
        </div>

        <!-- Main Headline -->
        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-slate-900 tracking-tight leading-tight">
            Khám phá những sự kiện <br class="hidden sm:inline" />
            <span class="bg-gradient-to-r from-blue-600 via-blue-500 to-sky-600 bg-clip-text text-transparent">
                nổi bật & truyền cảm hứng
            </span>
        </h1>

        <!-- Subtitle -->
        <p class="mt-5 text-base sm:text-lg text-slate-600 max-w-2xl mx-auto leading-relaxed">
            Tham gia các buổi workshop chuyên sâu, hội thảo công nghệ, tọa đàm kết nối và những hoạt động cộng đồng chất lượng.
        </p>

        <!-- Search & Filter Capsule Bar (Floating Bento Style) -->
        <form method="GET"
              action="{{ route('home') }}"
              class="mt-10 bg-white p-2.5 sm:p-3 rounded-2xl sm:rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-200/80 max-w-4xl mx-auto text-left">

            <div class="grid grid-cols-1 md:grid-cols-12 gap-2 sm:gap-3 items-center">

                <!-- Input Tìm kiếm -->
                <div class="md:col-span-5 relative flex items-center">
                    <div class="absolute left-3.5 text-slate-400 pointer-events-none">
                        <x-icons.search class="w-4 h-4" />
                    </div>
                    <input type="text"
                           name="q"
                           value="{{ request('q') }}"
                           placeholder="Tên sự kiện, địa điểm..."
                           class="w-full pl-10 pr-4 py-2.5 text-sm bg-slate-50 hover:bg-slate-100/70 focus:bg-white rounded-xl border border-transparent focus:border-blue-300 focus:ring-2 focus:ring-blue-100 transition placeholder:text-slate-400 font-medium">
                </div>

                <!-- Dropdown Chuyên mục -->
                <div class="md:col-span-3 relative flex items-center">
                    <div class="absolute left-3.5 text-slate-400 pointer-events-none">
                        <x-icons.tag class="w-4 h-4" />
                    </div>
                    <select name="category_id"
                            class="w-full pl-10 pr-8 py-2.5 text-sm bg-slate-50 hover:bg-slate-100/70 focus:bg-white rounded-xl border border-transparent focus:border-blue-300 focus:ring-2 focus:ring-blue-100 transition font-medium text-slate-700 cursor-pointer">
                        <option value="">Tất cả chuyên mục</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Dropdown Thời gian -->
                <div class="md:col-span-2 relative flex items-center">
                    <select name="period"
                            class="w-full px-3 py-2.5 text-sm bg-slate-50 hover:bg-slate-100/70 focus:bg-white rounded-xl border border-transparent focus:border-blue-300 focus:ring-2 focus:ring-blue-100 transition font-medium text-slate-700 cursor-pointer">
                        <option value="available" @selected($period === 'available')>Sắp / đang diễn ra</option>
                        <option value="upcoming" @selected($period === 'upcoming')>Chưa bắt đầu</option>
                        <option value="ended" @selected($period === 'ended')>Đã kết thúc</option>
                        <option value="all" @selected($period === 'all')>Tất cả</option>
                    </select>
                </div>

                <!-- Nút Submit Search -->
                <div class="md:col-span-2 flex items-center">
                    <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl text-sm font-bold bg-slate-900 text-white hover:bg-blue-600 transition shadow-sm hover:shadow-blue-500/20 active:scale-95 cursor-pointer">
                        <x-icons.search class="w-4 h-4" />
                        <span>Tìm kiếm</span>
                    </button>
                </div>

            </div>
        </form>

        <!-- Quick Filter Pills (Category Chips Bar) -->
        <div class="mt-6 flex flex-wrap items-center justify-center gap-2">
            <span class="text-xs font-semibold text-slate-400 mr-1 flex items-center gap-1">
                <x-icons.filter class="w-3.5 h-3.5" />
                Lọc nhanh:
            </span>

            <a href="{{ route('home', array_merge(request()->except(['category_id', 'page']))) }}"
               class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition border {{ !request('category_id') ? 'bg-slate-900 text-white border-slate-900 shadow-sm' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-100' }}">
                Tất cả
            </a>

            @foreach($categories as $category)
                <a href="{{ route('home', array_merge(request()->except('page'), ['category_id' => $category->id])) }}"
                   class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition border {{ request('category_id') == $category->id ? 'bg-blue-600 text-white border-blue-600 shadow-sm' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-100 hover:border-slate-300' }}">
                    {{ $category->name }}
                </a>
            @endforeach

            @if(request()->filled('q') || request()->filled('category_id') || request('period') !== 'available')
                <a href="{{ route('home') }}"
                   class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-xs font-medium text-rose-600 bg-rose-50 border border-rose-200 hover:bg-rose-100 transition ml-1">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Xóa lọc
                </a>
            @endif
        </div>

    </div>
</section>

<!-- Main Event Section -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    <!-- Section Title & Meta -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-2.5">
                <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">
                    {{ request()->filled('q') ? 'Kết quả tìm kiếm cho: "' . request('q') . '"' : 'Danh sách sự kiện' }}
                </h2>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-100">
                    {{ $events->total() }} sự kiện
                </span>
            </div>
            <p class="text-sm text-slate-500 mt-1">
                Lựa chọn sự kiện và đăng ký tham gia chỉ với một cú nhấp chuột.
            </p>
        </div>

        <!-- Quick Period Sorter Pill Tabs -->
        <div class="inline-flex items-center bg-slate-100 p-1 rounded-xl text-xs font-semibold text-slate-600 self-start sm:self-auto">
            <a href="{{ route('home', array_merge(request()->except('page'), ['period' => 'available'])) }}"
               class="px-3 py-1.5 rounded-lg transition {{ $period === 'available' ? 'bg-white text-blue-600 shadow-sm' : 'hover:text-slate-900' }}">
                Sắp diễn ra
            </a>
            <a href="{{ route('home', array_merge(request()->except('page'), ['period' => 'upcoming'])) }}"
               class="px-3 py-1.5 rounded-lg transition {{ $period === 'upcoming' ? 'bg-white text-blue-600 shadow-sm' : 'hover:text-slate-900' }}">
                Chưa bắt đầu
            </a>
            <a href="{{ route('home', array_merge(request()->except('page'), ['period' => 'all'])) }}"
               class="px-3 py-1.5 rounded-lg transition {{ $period === 'all' ? 'bg-white text-blue-600 shadow-sm' : 'hover:text-slate-900' }}">
                Tất cả
            </a>
        </div>
    </div>

    <!-- Event Cards Grid (Luma Bento Style) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-7">

        @forelse($events as $event)
            @php
                $isEnded = now()->greaterThan($event->ends_at);
                $isStarted = now()->greaterThanOrEqualTo($event->starts_at);
                $isFull = $event->registered_count >= $event->capacity;
                $fillPercent = $event->capacity > 0 ? min(100, round(($event->registered_count / $event->capacity) * 100)) : 0;
            @endphp

            <article class="group relative flex flex-col bg-white rounded-3xl border border-slate-200/80 hover:border-blue-200/80 shadow-sm hover:shadow-xl hover:shadow-blue-500/10 hover:-translate-y-1 transition-all duration-300 overflow-hidden">

                <!-- Image Area with Glass Badges -->
                <a href="{{ route('events.show', $event->slug) }}" class="block relative aspect-[16/10] overflow-hidden bg-slate-100">
                    @if($event->cover_image)
                        <img src="{{ asset('storage/' . $event->cover_image) }}"
                             alt="{{ $event->title }}"
                             loading="lazy"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out">
                    @else
                        <div class="w-full h-full bg-gradient-to-tr from-blue-100 via-slate-100 to-blue-50 flex items-center justify-center">
                            <div class="text-center p-4">
                                <div class="w-12 h-12 mx-auto rounded-2xl bg-white shadow-sm flex items-center justify-center text-blue-500 mb-2">
                                    <x-icons.ticket class="w-6 h-6" />
                                </div>
                                <span class="text-sm font-bold text-blue-700 tracking-wide">EventHub</span>
                            </div>
                        </div>
                    @endif

                    <!-- Category Badge (Top Left Glassmorphism) -->
                    <div class="absolute top-3.5 left-3.5">
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-white/90 backdrop-blur-md text-slate-800 shadow-sm border border-white/50">
                            {{ $event->category->name }}
                        </span>
                    </div>

                    <!-- Status Badge (Top Right) -->
                    <div class="absolute top-3.5 right-3.5">
                        @if($isEnded)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-slate-800/80 backdrop-blur-md text-slate-200 border border-slate-700/50">
                                Đã kết thúc
                            </span>
                        @elseif($isFull)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-rose-600/90 backdrop-blur-md text-white border border-rose-500/50">
                                Đã kín chỗ
                            </span>
                        @elseif($isStarted)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-600/90 backdrop-blur-md text-white border border-emerald-500/50">
                                <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                                Đang diễn ra
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-blue-600/90 backdrop-blur-md text-white border border-blue-500/50">
                                Mở đăng ký
                            </span>
                        @endif
                    </div>
                </a>

                <!-- Card Body (Luma Bento Style) -->
                <div class="flex-1 p-5 flex flex-col justify-between">

                    <div>
                        <!-- Bento Header: Mini Date Card + Event Title -->
                        <div class="flex items-start gap-3.5">
                            <!-- Mini Date Block -->
                            <div class="w-14 h-14 rounded-2xl bg-blue-50/80 border border-blue-100 flex flex-col items-center justify-center shrink-0 text-center shadow-sm group-hover:bg-blue-600 group-hover:border-blue-600 transition-colors duration-300">
                                <span class="text-[10px] font-extrabold uppercase tracking-wider text-blue-600 group-hover:text-blue-100 transition-colors">
                                    Thg {{ $event->starts_at->format('m') }}
                                </span>
                                <span class="text-xl font-black text-slate-900 group-hover:text-white leading-none mt-0.5 transition-colors">
                                    {{ $event->starts_at->format('d') }}
                                </span>
                            </div>

                            <!-- Title & Time -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-1.5 text-xs font-semibold text-blue-600 mb-1">
                                    <x-icons.clock class="w-3.5 h-3.5" />
                                    <span>{{ $event->starts_at->format('H:i') }} - {{ $event->ends_at->format('H:i') }}</span>
                                </div>
                                <h3 class="text-base font-bold text-slate-900 group-hover:text-blue-600 transition-colors line-clamp-2 leading-snug">
                                    <a href="{{ route('events.show', $event->slug) }}">
                                        {{ $event->title }}
                                    </a>
                                </h3>
                            </div>
                        </div>

                        <!-- Location & Attendees (Clean SVG Icons, NO raw emojis) -->
                        <div class="mt-4 pt-3 border-t border-slate-100 space-y-2 text-xs text-slate-600 font-medium">

                            <!-- Location -->
                            <div class="flex items-center gap-2">
                                <x-icons.map-pin class="w-4 h-4 text-slate-400 shrink-0" />
                                <span class="truncate">{{ $event->location }}</span>
                            </div>

                            <!-- Capacity / Attendees -->
                            <div class="flex items-center justify-between gap-2">
                                <div class="flex items-center gap-2">
                                    <x-icons.users class="w-4 h-4 text-slate-400 shrink-0" />
                                    <span>{{ $event->registered_count }} / {{ $event->capacity }} người tham gia</span>
                                </div>
                                <span class="text-[11px] font-semibold {{ $isFull ? 'text-rose-600' : 'text-slate-400' }}">
                                    {{ $fillPercent }}%
                                </span>
                            </div>

                            <!-- Visual Capacity Progress Bar -->
                            <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden mt-1">
                                <div class="h-full rounded-full transition-all duration-500 {{ $fillPercent >= 90 ? 'bg-rose-500' : ($fillPercent >= 60 ? 'bg-amber-500' : 'bg-blue-600') }}"
                                     style="width: {{ $fillPercent }}%">
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Card Action Footer -->
                    <div class="mt-5 pt-3.5 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-xs text-slate-400 font-medium">
                            Hạn ĐK: {{ $event->registration_deadline->format('d/m') }}
                        </span>

                        <a href="{{ route('events.show', $event->slug) }}"
                           class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-600 group-hover:text-blue-700 group-hover:translate-x-0.5 transition-all">
                            <span>Chi tiết sự kiện</span>
                            <x-icons.arrow-right class="w-3.5 h-3.5" />
                        </a>
                    </div>

                </div>

            </article>

        @empty

            <!-- Modern Empty State -->
            <div class="md:col-span-2 lg:col-span-3 text-center py-16 px-4 bg-white rounded-3xl border border-slate-200/80 shadow-sm">
                <div class="w-16 h-16 mx-auto rounded-3xl bg-blue-50 text-blue-500 flex items-center justify-center mb-4">
                    <x-icons.search class="w-7 h-7" />
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-1">
                    Không tìm thấy sự kiện nào
                </h3>
                <p class="text-sm text-slate-500 max-w-md mx-auto mb-6">
                    Không có sự kiện nào khớp với tiêu chí tìm kiếm hoặc bộ lọc hiện tại của bạn.
                </p>
                <a href="{{ route('home') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-sm font-semibold bg-slate-900 text-white hover:bg-blue-600 transition shadow-sm">
                    <span>Xóa bộ lọc & Xem tất cả</span>
                </a>
            </div>

        @endforelse

    </div>

    <!-- Pagination -->
    @if($events->hasPages())
        <div class="mt-12 flex justify-center">
            {{ $events->links() }}
        </div>
    @endif

</section>

@endsection

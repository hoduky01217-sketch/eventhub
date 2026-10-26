@extends('layouts.public')

@section('title', $event->title . ' - EventHub')

@section('content')

@php
    $isEnded = now()->greaterThan($event->ends_at);
    $isStarted = now()->greaterThanOrEqualTo($event->starts_at);
    $fillPercent = $event->capacity > 0 ? min(100, round(($event->registered_count / $event->capacity) * 100)) : 0;
@endphp

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">

    <!-- Breadcrumb & Back Link -->
    <div class="flex items-center justify-between mb-6">
        <a href="{{ route('home') }}"
           class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-blue-600 transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Quay lại danh sách</span>
        </a>

        <div class="hidden sm:flex items-center gap-2 text-xs font-medium text-slate-400">
            <a href="{{ route('home') }}" class="hover:text-slate-600">Trang chủ</a>
            <span>/</span>
            <span>{{ $event->category->name }}</span>
            <span>/</span>
            <span class="text-slate-600 truncate max-w-[200px]">{{ $event->title }}</span>
        </div>
    </div>

    <!-- Main Grid Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        <!-- Left Column: Event Hero & Content (8 cols) -->
        <div class="lg:col-span-8 space-y-8">

            <!-- Event Cover Image Container -->
            <div class="relative rounded-3xl overflow-hidden bg-slate-900 border border-slate-200/80 shadow-md aspect-[16/9] w-full">
                @if($event->cover_image)
                    <img src="{{ asset('storage/' . $event->cover_image) }}"
                         alt="{{ $event->title }}"
                         class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full bg-gradient-to-tr from-blue-900 via-slate-900 to-sky-900 flex items-center justify-center">
                        <div class="text-center p-6">
                            <div class="w-16 h-16 mx-auto rounded-3xl bg-white/10 backdrop-blur-md text-white flex items-center justify-center mb-3">
                                <x-icons.ticket class="w-8 h-8 text-blue-400" />
                            </div>
                            <span class="text-xl font-black text-white tracking-wide">EventHub</span>
                        </div>
                    </div>
                @endif

                <!-- Glassmorphism Category Badge on Top-Left -->
                <div class="absolute top-4 left-4">
                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold bg-white/95 backdrop-blur-md text-slate-900 shadow-md border border-white/50">
                        <x-icons.tag class="w-3.5 h-3.5 text-blue-600" />
                        {{ $event->category->name }}
                    </span>
                </div>
            </div>

            <!-- Title & Meta Header -->
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm">
                <h1 class="text-2xl sm:text-4xl font-black text-slate-900 tracking-tight leading-snug">
                    {{ $event->title }}
                </h1>

                <!-- Bento Info Grid (4 Mini Cards - No raw emojis) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-8">

                    <!-- Bắt đầu -->
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 flex items-start gap-3.5">
                        <div class="w-10 h-10 rounded-xl bg-blue-100/80 text-blue-600 flex items-center justify-center shrink-0">
                            <x-icons.calendar class="w-5 h-5" />
                        </div>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Thời gian bắt đầu</p>
                            <p class="text-sm font-bold text-slate-900 mt-0.5">
                                {{ $event->starts_at->format('d/m/Y') }} lúc {{ $event->starts_at->format('H:i') }}
                            </p>
                        </div>
                    </div>

                    <!-- Kết thúc -->
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 flex items-start gap-3.5">
                        <div class="w-10 h-10 rounded-xl bg-sky-100/80 text-sky-600 flex items-center justify-center shrink-0">
                            <x-icons.clock class="w-5 h-5" />
                        </div>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Thời gian kết thúc</p>
                            <p class="text-sm font-bold text-slate-900 mt-0.5">
                                {{ $event->ends_at->format('d/m/Y') }} lúc {{ $event->ends_at->format('H:i') }}
                            </p>
                        </div>
                    </div>

                    <!-- Địa điểm -->
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 flex items-start gap-3.5">
                        <div class="w-10 h-10 rounded-xl bg-rose-100/80 text-rose-600 flex items-center justify-center shrink-0">
                            <x-icons.map-pin class="w-5 h-5" />
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Địa điểm tổ chức</p>
                            <p class="text-sm font-bold text-slate-900 mt-0.5 break-words">
                                {{ $event->location }}
                            </p>
                        </div>
                    </div>

                    <!-- Hạn đăng ký -->
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 flex items-start gap-3.5">
                        <div class="w-10 h-10 rounded-xl bg-amber-100/80 text-amber-600 flex items-center justify-center shrink-0">
                            <x-icons.ticket class="w-5 h-5" />
                        </div>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Hạn chót đăng ký</p>
                            <p class="text-sm font-bold text-slate-900 mt-0.5">
                                {{ $event->registration_deadline->format('d/m/Y H:i') }}
                            </p>
                        </div>
                    </div>

                </div>

                <!-- Giới thiệu sự kiện -->
                <div class="mt-8 pt-8 border-t border-slate-100">
                    <h2 class="text-xl font-black text-slate-900 mb-4 flex items-center gap-2">
                        <span>Giới thiệu sự kiện</span>
                    </h2>

                    <div class="prose max-w-none text-slate-700 leading-relaxed font-normal whitespace-pre-line text-sm sm:text-base">
{{ $event->description }}
                    </div>
                </div>

            </div>

        </div>

        <!-- Right Column: Registration Bento Card (4 cols, Sticky) -->
        <div class="lg:col-span-4">
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-lg shadow-slate-200/50 p-6 sticky top-28 space-y-6">

                <!-- Header Registration Box -->
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-blue-600">Thông tin vé</span>
                    <h3 class="text-2xl font-black text-slate-900 mt-1">Đăng ký tham gia</h3>
                </div>

                <!-- Capacity & Progress Meter -->
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-3">
                    <div class="flex items-center justify-between text-xs font-semibold">
                        <span class="text-slate-500">Đã đăng ký:</span>
                        <span class="text-slate-900 font-bold">
                            {{ $event->registered_count }} / {{ $event->capacity }} chỗ
                        </span>
                    </div>

                    <!-- Meter -->
                    <div class="w-full h-2 bg-slate-200/80 rounded-full overflow-hidden">
                        <div class="h-full rounded-full transition-all duration-500 {{ $fillPercent >= 90 ? 'bg-rose-500' : ($fillPercent >= 60 ? 'bg-amber-500' : 'bg-blue-600') }}"
                             style="width: {{ $fillPercent }}%"></div>
                    </div>

                    <div class="flex items-center justify-between text-xs">
                        <span class="text-slate-400">Tỷ lệ: {{ $fillPercent }}%</span>
                        <span class="font-bold {{ $remaining > 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                            {{ $remaining > 0 ? 'Còn ' . $remaining . ' chỗ trống' : 'Hết chỗ trống' }}
                        </span>
                    </div>
                </div>

                <!-- Action Button Area -->
                <div class="space-y-3 pt-2">

                    @guest
                        <div class="p-4 rounded-2xl bg-blue-50/70 border border-blue-100 text-xs text-blue-900 leading-relaxed">
                            <p class="font-semibold">Bạn chưa đăng nhập?</p>
                            <p class="mt-1 text-slate-600">Vui lòng đăng nhập hoặc tạo tài khoản miễn phí để nhận vé tham gia sự kiện này.</p>
                        </div>

                        <a href="{{ route('login') }}"
                           class="w-full inline-flex items-center justify-center gap-2 py-3 px-5 rounded-2xl text-sm font-bold bg-slate-900 text-white hover:bg-blue-600 transition shadow-md shadow-slate-900/10 active:scale-95">
                            <span>Đăng nhập để đăng ký</span>
                            <x-icons.arrow-right class="w-4 h-4" />
                        </a>

                    @else
                        @if(Auth::user()->isAdmin())
                            <div class="p-4 rounded-2xl bg-slate-100 border border-slate-200 text-xs text-slate-700">
                                <p class="font-bold text-slate-900 mb-1">Tài khoản Quản trị viên</p>
                                Bạn đang đăng nhập bằng quyền Admin. Hãy sử dụng tài khoản User để thao tác đăng ký vé.
                            </div>
                        @elseif($registration && $registration->status === 'registered')
                            <!-- Đã đăng ký thành công -->
                            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 space-y-2">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-emerald-600 text-white flex items-center justify-center shrink-0">
                                        <x-icons.check class="w-3.5 h-3.5" />
                                    </div>
                                    <span class="text-sm font-bold">Bạn đã đăng ký tham gia</span>
                                </div>
                                <p class="text-xs text-emerald-700">
                                    Đăng ký lúc: {{ $registration->registered_at->format('d/m/Y H:i') }}
                                </p>
                                @if($registration->checked_in_at)
                                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                        ✓ Đã hoàn tất check-in
                                    </div>
                                @endif
                            </div>

                            @if(!$registration->checked_in_at && now()->lessThan($event->starts_at))
                                <form method="POST"
                                      action="{{ route('registrations.destroy', $registration) }}"
                                      onsubmit="return confirm('Bạn có chắc chắn muốn hủy đăng ký tham gia sự kiện này?')">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="w-full py-2.5 px-4 rounded-xl text-xs font-bold text-rose-600 hover:text-white border border-rose-200 hover:bg-rose-600 transition">
                                        Hủy đăng ký sự kiện
                                    </button>
                                </form>
                            @endif

                            <a href="{{ route('registrations.index') }}"
                               class="w-full inline-flex items-center justify-center gap-2 py-3 px-4 rounded-2xl text-sm font-bold bg-slate-100 text-slate-800 hover:bg-slate-200 transition">
                                <x-icons.ticket class="w-4 h-4 text-slate-500" />
                                <span>Xem các sự kiện của tôi</span>
                            </a>

                        @elseif($canRegister)
                            <!-- Có thể đăng ký -->
                            <form method="POST" action="{{ route('registrations.store', $event) }}">
                                @csrf
                                <button type="submit"
                                        class="w-full inline-flex items-center justify-center gap-2 py-3.5 px-5 rounded-2xl text-sm font-bold bg-gradient-to-r from-blue-600 to-blue-600 text-white hover:from-blue-700 hover:to-blue-700 shadow-md shadow-blue-500/20 active:scale-95 transition">
                                    <x-icons.ticket class="w-4 h-4" />
                                    <span>{{ $registration ? 'Đăng ký lại tham gia' : 'Đăng ký tham gia ngay' }}</span>
                                </button>
                            </form>
                        @else
                            <!-- Không thể đăng ký -->
                            <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-xs text-amber-900 leading-relaxed">
                                <p class="font-bold mb-1">Hiện không thể đăng ký</p>
                                @if(now()->greaterThanOrEqualTo($event->starts_at))
                                    Sự kiện đã bắt đầu hoặc đã kết thúc.
                                @elseif(now()->greaterThan($event->registration_deadline))
                                    Đã hết thời hạn đăng ký sự kiện.
                                @elseif($remaining <= 0)
                                    Sự kiện đã đủ số lượng người đăng ký.
                                @else
                                    Sự kiện hiện chưa mở hoặc đã tạm đóng.
                                @endif
                            </div>
                        @endif
                    @endguest

                </div>

            </div>
        </div>

    </div>

</div>

@endsection

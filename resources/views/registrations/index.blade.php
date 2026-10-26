@extends('layouts.public')

@section('title', 'Sự kiện của tôi - EventHub')

@section('content')

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 pb-6 border-b border-slate-200/80">
        <div>
            <div class="flex items-center gap-2.5">
                <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <x-icons.ticket class="w-5 h-5" />
                </div>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                        Sự kiện của tôi
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                        Quản lý vé và danh sách các sự kiện bạn đã đăng ký tham gia.
                    </p>
                </div>
            </div>
        </div>

        <a href="{{ route('home') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-bold bg-slate-900 text-white hover:bg-blue-600 transition shadow-sm self-start sm:self-auto">
            <x-icons.search class="w-3.5 h-3.5" />
            <span>Khám phá thêm sự kiện</span>
        </a>
    </div>

    @if($errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 mb-6 text-sm">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="space-y-4">

        @forelse($registrations as $registration)
            @php
                $event = $registration->event;
                $isEnded = now()->greaterThan($event->ends_at);
            @endphp

            <div class="group bg-white border border-slate-200/80 hover:border-blue-200 rounded-3xl p-5 sm:p-6 shadow-sm hover:shadow-lg hover:shadow-blue-500/5 transition-all duration-300 flex flex-col md:flex-row gap-6 items-start md:items-center justify-between">

                <!-- Left: Image & Event info -->
                <div class="flex flex-col sm:flex-row gap-5 items-start sm:items-center flex-1 min-w-0">

                    <!-- Thumbnail Image -->
                    <div class="relative w-full sm:w-44 h-32 rounded-2xl overflow-hidden bg-slate-100 shrink-0">
                        @if($event->cover_image)
                            <img src="{{ asset('storage/' . $event->cover_image) }}"
                                 alt="{{ $event->title }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full bg-gradient-to-tr from-blue-50 to-sky-100 flex items-center justify-center text-blue-400">
                                <x-icons.ticket class="w-8 h-8" />
                            </div>
                        @endif

                        <!-- Date Badge Overlay -->
                        <div class="absolute top-2.5 left-2.5 px-2 py-1 rounded-xl bg-white/95 backdrop-blur-md text-center shadow-sm">
                            <span class="block text-[9px] font-black text-blue-600 uppercase">Thg {{ $event->starts_at->format('m') }}</span>
                            <span class="block text-sm font-black text-slate-900 leading-none">{{ $event->starts_at->format('d') }}</span>
                        </div>
                    </div>

                    <!-- Meta & Details -->
                    <div class="flex-1 min-w-0 space-y-2">
                        <!-- Badges -->
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-700">
                                {{ $event->category->name }}
                            </span>

                            @if($registration->status === 'registered')
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Đã đăng ký
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                    Đã hủy
                                </span>
                            @endif

                            @if($registration->checked_in_at)
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                    <x-icons.check class="w-3 h-3" />
                                    Đã check-in
                                </span>
                            @endif
                        </div>

                        <!-- Title -->
                        <h2 class="text-base sm:text-lg font-bold text-slate-900 group-hover:text-blue-600 transition-colors line-clamp-1">
                            {{ $event->title }}
                        </h2>

                        <!-- Info rows (Clean SVG Icons) -->
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-500 font-medium">
                            <div class="flex items-center gap-1.5">
                                <x-icons.clock class="w-3.5 h-3.5 text-slate-400" />
                                <span>{{ $event->starts_at->format('d/m/Y - H:i') }}</span>
                            </div>

                            <div class="flex items-center gap-1.5">
                                <x-icons.map-pin class="w-3.5 h-3.5 text-slate-400" />
                                <span class="truncate max-w-[200px]">{{ $event->location }}</span>
                            </div>
                        </div>

                        <p class="text-[11px] text-slate-400 pt-0.5">
                            Đăng ký lúc: {{ $registration->registered_at->format('d/m/Y H:i') }}
                        </p>
                    </div>

                </div>

                <!-- Right: Actions -->
                <div class="w-full md:w-auto flex flex-row md:flex-col gap-2 shrink-0 pt-3 md:pt-0 border-t md:border-t-0 border-slate-100">

                    @if($event->status === 'published' && $event->category->is_active)
                        <a href="{{ route('events.show', $event->slug) }}"
                           class="flex-1 md:flex-none text-center px-4 py-2.5 rounded-xl text-xs font-bold bg-slate-900 text-white hover:bg-blue-600 transition shadow-sm">
                            Xem chi tiết
                        </a>
                    @else
                        <span class="text-xs text-slate-400 italic py-2">
                            Sự kiện ẩn
                        </span>
                    @endif

                    @if(
                        $registration->status === 'registered' &&
                        !$registration->checked_in_at &&
                        now()->lessThan($event->starts_at)
                    )
                        <form method="POST"
                              action="{{ route('registrations.destroy', $registration) }}"
                              onsubmit="return confirm('Bạn có chắc chắn muốn hủy đăng ký tham gia sự kiện này?')">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="w-full text-center px-4 py-2 rounded-xl text-xs font-semibold text-rose-600 hover:text-white border border-rose-200 hover:bg-rose-600 transition">
                                Hủy vé
                            </button>
                        </form>
                    @endif

                </div>

            </div>

        @empty

            <!-- Empty State -->
            <div class="bg-white border border-slate-200/80 rounded-3xl p-12 text-center shadow-sm">
                <div class="w-14 h-14 mx-auto rounded-2xl bg-blue-50 text-blue-500 flex items-center justify-center mb-4">
                    <x-icons.ticket class="w-7 h-7" />
                </div>
                <h3 class="text-base font-bold text-slate-900 mb-1">
                    Bạn chưa đăng ký sự kiện nào
                </h3>
                <p class="text-xs text-slate-500 max-w-sm mx-auto mb-6">
                    Hãy dạo qua các sự kiện công nghệ, workshop và diễn đàn đang mở đăng ký để không bỏ lỡ trải nghiệm thú vị nhé.
                </p>
                <a href="{{ route('home') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-xs font-bold bg-blue-600 text-white hover:bg-blue-700 transition shadow-sm">
                    <span>Khám phá ngay</span>
                    <x-icons.arrow-right class="w-3.5 h-3.5" />
                </a>
            </div>

        @endforelse

    </div>

    @if($registrations->hasPages())
        <div class="mt-8 flex justify-center">
            {{ $registrations->links() }}
        </div>
    @endif

</div>

@endsection

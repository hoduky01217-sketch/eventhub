@extends('layouts.public')

@section('title', 'Vé tham gia sự kiện - EventHub')

@section('content')

<div class="max-w-3xl mx-auto px-4 py-10">

    <a href="{{ route('registrations.index') }}"
       class="inline-flex items-center gap-1.5 text-sm font-semibold text-blue-600 hover:text-blue-700 transition">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        <span>Quay lại sự kiện của tôi</span>
    </a>

    <div class="bg-white border border-slate-200/80 rounded-3xl shadow-xl
                overflow-hidden mt-6">

        <!-- Phần đầu vé -->
        <div class="bg-gradient-to-r from-blue-600 to-sky-600
                    text-white px-8 py-8 text-center">

            <div class="w-12 h-12 mx-auto rounded-2xl bg-white/10 backdrop-blur-md flex items-center justify-center mb-3">
                <x-icons.ticket class="w-6 h-6 text-white" />
            </div>

            <h1 class="text-3xl font-black mb-1 tracking-tight">
                EventHub
            </h1>

            <p class="text-blue-100 text-xs font-bold tracking-widest uppercase">
                VÉ THAM GIA SỰ KIỆN CHÍNH THỨC
            </p>
        </div>

        <!-- Thông tin người tham gia -->
        <div class="p-8">

            <div class="text-center mb-8">

                <h2 class="text-2xl font-black text-slate-900">
                    {{ $registration->event->title }}
                </h2>

                <p class="text-slate-500 text-sm mt-1">
                    {{ $registration->event->category->name }}
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">

                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">
                        Người tham gia
                    </p>
                    <p class="font-bold text-slate-800 mt-1">
                        {{ $registration->user->name }}
                    </p>
                </div>

                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">
                        Mã đăng ký
                    </p>
                    <p class="font-mono font-bold text-blue-600 mt-1">
                        #{{ $registration->id }}
                    </p>
                </div>

                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">
                        Thời gian
                    </p>
                    <p class="font-bold text-slate-800 mt-1">
                        {{ $registration->event->starts_at->format('d/m/Y H:i') }}
                    </p>
                </div>

                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">
                        Địa điểm
                    </p>
                    <p class="font-bold text-slate-800 mt-1">
                        {{ $registration->event->location }}
                    </p>
                </div>

            </div>

            <!-- QR Code -->
            <div class="border-t border-dashed border-slate-200 pt-8 text-center">

                <h3 class="text-base font-bold text-slate-800 mb-4">
                    Mã QR Check-in
                </h3>

                <div class="inline-block bg-white border border-slate-200
                            rounded-2xl p-4 shadow-sm">

                    <img src="{{ $qrImage }}"
                         alt="Mã QR vé tham gia"
                         class="w-64 h-64 mx-auto">
                </div>

                
<p class="text-xs text-slate-500 mt-4">
    Xuất trình mã QR này cho ban tổ chức tại sự kiện để hoàn tất check-in.
</p>

<!-- Hiển thị mã vé khi không quét được QR -->
                <details class="mt-5 text-left border border-slate-200 rounded-xl p-4">
                    <summary class="cursor-pointer text-sm font-semibold text-blue-600">
                        Không quét được QR? Hiển thị mã vé
                    </summary>

                    <p class="mt-3 text-xs text-slate-500">
                        Chỉ cung cấp mã này cho Admin khi check-in.
                    </p>

                    <code class="block mt-2 p-3 bg-slate-100 rounded-lg
                                text-xs break-all select-all text-slate-800">
                        {{ $registration->qr_token }}
                    </code>
                </details>

                @if($registration->checked_in_at)
                    <div class="mt-6 bg-emerald-50 text-emerald-800 border border-emerald-200
                                rounded-2xl p-4 text-sm font-semibold">
                        ✓ Đã check-in lúc
                        {{ $registration->checked_in_at->format('d/m/Y H:i') }}
                    </div>
                @elseif($registration->event->status === 'cancelled')
                    <div class="mt-6 bg-rose-50 text-rose-700 border border-rose-200
                                rounded-2xl p-4 text-sm font-semibold">
                        Sự kiện đã bị hủy. Vé không còn hiệu lực.
                    </div>
                @else
                    <div class="mt-6 bg-amber-50 text-amber-800 border border-amber-200
                                rounded-2xl p-4 text-sm font-semibold">
                        Chưa check-in
                    </div>
                @endif

                    <div class="mt-6 bg-emerald-50 text-emerald-800 border border-emerald-200
                                rounded-2xl p-4 text-sm font-semibold">
                        ✓ Đã check-in lúc {{ $registration->checked_in_at->format('d/m/Y H:i') }}
                    </div>
                @elseif($registration->event->status === 'cancelled')
                    <div class="mt-6 bg-rose-50 text-rose-700 border border-rose-200
                                rounded-2xl p-4 text-sm font-semibold">
                        Sự kiện đã bị hủy. Vé không còn hiệu lực.
                    </div>
                @else
                    <div class="mt-6 bg-amber-50 text-amber-800 border border-amber-200
                                rounded-2xl p-4 text-sm font-semibold">
                        Chưa check-in
                    </div>
                @endif

            </div>

            <div class="mt-8 text-center print:hidden">
                <button onclick="window.print()"
                        class="bg-blue-600 hover:bg-blue-700
                               text-white px-8 py-3 rounded-2xl font-bold shadow-md shadow-blue-500/20 transition cursor-pointer">
                    In vé sự kiện
                </button>
            </div>

        </div>
    </div>

</div>

@endsection

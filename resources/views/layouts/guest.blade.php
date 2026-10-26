<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'EventHub') }} - Tài khoản</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-full font-sans antialiased bg-slate-50 text-slate-800 selection:bg-blue-500 selection:text-white relative">

    <!-- Ambient Glow Effects (Blue Theme) -->
    <div class="fixed top-0 left-1/2 -translate-x-1/2 w-[700px] h-[400px] bg-gradient-to-tr from-blue-200/40 via-sky-200/30 to-transparent blur-3xl -z-10 pointer-events-none rounded-full"></div>

    <div class="min-h-screen flex flex-col justify-center items-center px-4 py-10 sm:px-6 lg:px-8">

        <!-- Top Header: Logo & Back to Home (Căn chỉnh đúng chiều rộng của Form Card) -->
        <div class="w-full max-w-md mx-auto mb-6 flex items-center justify-between">
            <a href="{{ route('home') }}" class="group flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-blue-600 to-sky-500 flex items-center justify-center text-white shadow-md shadow-blue-500/20 group-hover:scale-105 transition-transform">
                    <x-icons.ticket class="w-5 h-5" />
                </div>
                <span class="text-xl font-black tracking-tight text-slate-900 group-hover:text-blue-600 transition-colors">
                    Event<span class="text-blue-600">Hub</span>
                </span>
            </a>

            <a href="{{ route('home') }}"
               class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold text-slate-600 bg-white hover:bg-slate-100 hover:text-slate-900 border border-slate-200/80 shadow-sm transition">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Về trang chủ</span>
            </a>
        </div>

        <!-- Main Centered Card Container -->
        <div class="w-full max-w-md bg-white rounded-3xl border border-slate-200/80 shadow-xl shadow-slate-200/60 p-7 sm:p-9 relative z-10">
            {{ $slot }}
        </div>

        <!-- Bottom Copyright Text -->
        <div class="text-center text-xs text-slate-400 mt-6">
            <p>&copy; {{ date('Y') }} EventHub. Đồ Án Lập Trình Mã Nguồn Mở.</p>
        </div>

    </div>

</body>
</html>

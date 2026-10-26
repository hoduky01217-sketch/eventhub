<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'EventHub') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-50 text-slate-800 min-h-screen">
        @if(Auth::check() && Auth::user()->isAdmin())
            <!-- ================= ADMIN SAAS PRO LAYOUT (PHƯƠNG ÁN 1) ================= -->
            <div x-data="{ sidebarOpen: false }" class="min-h-screen flex bg-slate-50">
                
                <!-- Mobile Backdrop -->
                <div x-show="sidebarOpen" 
                     x-transition:enter="transition-opacity ease-linear duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition-opacity ease-linear duration-300"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     @click="sidebarOpen = false"
                     class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-xs md:hidden"
                     style="display: none;"></div>

                <!-- Left Sidebar -->
                <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
                       class="fixed md:sticky top-0 left-0 z-50 h-screen w-64 bg-white border-r border-slate-200/80 flex flex-col justify-between shrink-0 transition-transform duration-300 ease-in-out">
                    
                    <div class="flex-1 flex flex-col min-h-0 overflow-y-auto">
                        <!-- Brand Header -->
                        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-600 via-blue-500 to-sky-500 flex items-center justify-center text-white shadow-md shadow-blue-500/20">
                                    <x-icons.ticket class="w-5 h-5 text-white" />
                                </div>
                                <div>
                                    <span class="text-xl font-black tracking-tight text-slate-900 block leading-tight">Event<span class="text-blue-600">Hub</span></span>
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Admin Console</span>
                                </div>
                            </a>

                            <!-- Close button for mobile -->
                            <button @click="sidebarOpen = false" class="md:hidden text-slate-400 hover:text-slate-600 p-1">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        <!-- Navigation Links -->
                        <div class="p-4 space-y-6">
                            <!-- Group: TỔNG QUAN -->
                            <div>
                                <p class="px-3 text-[10px] font-extrabold uppercase tracking-widest text-slate-400 mb-2">Tổng quan</p>
                                <nav class="space-y-1">
                                    <a href="{{ route('admin.dashboard') }}"
                                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-700 shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                                        <x-icons.chart-bar class="w-4 h-4 {{ request()->routeIs('admin.dashboard') ? 'text-blue-600' : 'text-slate-400' }}" />
                                        <span>Dashboard</span>
                                    </a>

                                    <a href="{{ route('admin.events.index') }}"
                                       class="flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('admin.events.*') ? 'bg-blue-50 text-blue-700 font-bold shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                                        <div class="flex items-center gap-3">
                                            <x-icons.calendar class="w-4 h-4 {{ request()->routeIs('admin.events.*') ? 'text-blue-600' : 'text-slate-400' }}" />
                                            <span>Quản lý sự kiện</span>
                                        </div>
                                    </a>

                                    <a href="{{ route('admin.categories.index') }}"
                                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('admin.categories.*') ? 'bg-blue-50 text-blue-700 font-bold shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                                        <x-icons.tag class="w-4 h-4 {{ request()->routeIs('admin.categories.*') ? 'text-blue-600' : 'text-slate-400' }}" />
                                        <span>Quản lý chuyên mục</span>
                                    </a>
                                </nav>
                            </div>

                            <!-- Group: VẬN HÀNH & CHECK-IN -->
                            <div>
                                <p class="px-3 text-[10px] font-extrabold uppercase tracking-widest text-slate-400 mb-2">Vận hành</p>
                                <nav class="space-y-1">
                                    <a href="{{ route('admin.checkin.index') }}"
                                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('admin.checkin.*') ? 'bg-blue-50 text-blue-700 font-bold shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                                        <x-icons.qr-code class="w-4 h-4 {{ request()->routeIs('admin.checkin.*') ? 'text-blue-600' : 'text-slate-400' }}" />
                                        <span>Quét QR Check-in</span>
                                    </a>
                                </nav>
                            </div>

                            <!-- Group: KHÁM PHÁ -->
                            <div>
                                <p class="px-3 text-[10px] font-extrabold uppercase tracking-widest text-slate-400 mb-2">Cổng sự kiện</p>
                                <nav class="space-y-1">
                                    <a href="{{ route('home') }}"
                                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition">
                                        <x-icons.sparkles class="w-4 h-4 text-slate-400" />
                                        <span>Xem trang khám phá</span>
                                    </a>
                                </nav>
                            </div>
                        </div>
                    </div>

                    <!-- Admin Profile Card -->
                    <div class="p-4 border-t border-slate-100">
                        <div class="p-3 bg-slate-50 rounded-2xl flex items-center justify-between">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-9 h-9 rounded-xl bg-blue-600 text-white font-black text-sm flex items-center justify-center relative shadow-xs shrink-0">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 ring-2 ring-white absolute -bottom-0.5 -right-0.5"></span>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-bold text-slate-900 truncate">{{ Auth::user()->name }}</p>
                                    <p class="text-[10px] text-slate-400 truncate">Quản trị viên</p>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" title="Đăng xuất" class="p-1.5 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-white transition cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </aside>

                <!-- Right Main Content Container -->
                <div class="flex-1 flex flex-col min-w-0">
                    
                    <!-- Topbar -->
                    <header class="bg-white/90 backdrop-blur-md border-b border-slate-200/80 h-16 flex items-center justify-between px-4 sm:px-6 lg:px-8 sticky top-0 z-30">
                        <div class="flex items-center gap-4">
                            <!-- Mobile Toggle -->
                            <button @click="sidebarOpen = true" class="md:hidden p-2 rounded-xl text-slate-500 hover:bg-slate-100 transition cursor-pointer">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                            </button>

                            <div class="text-xs text-slate-500 font-medium hidden sm:block">
                                <span class="text-slate-400">Admin</span> / <span class="font-bold text-slate-800">Trung tâm điều hành</span>
                            </div>

                            <div class="hidden lg:flex items-center gap-2 pl-4 border-l border-slate-200 text-xs text-slate-500">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                <span>Hệ thống trực tuyến</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.events.create') }}"
                               class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold bg-blue-600 text-white hover:bg-blue-700 shadow-sm shadow-blue-500/20 transition">
                                <x-icons.plus class="w-3.5 h-3.5 text-white" />
                                <span>Tạo sự kiện mới</span>
                            </a>
                        </div>
                    </header>

                    <!-- Main Slot -->
                    <main class="flex-1">
                        {{ $slot }}
                    </main>

                </div>

            </div>

        @else
            <!-- ================= USER / GUEST APP LAYOUT ================= -->
            <div class="min-h-screen bg-slate-50">
                @include('layouts.navigation')

                @isset($header)
                    <header class="bg-white border-b border-slate-200/80">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <main>
                    {{ $slot }}
                </main>
            </div>
        @endif
    </body>
</html>

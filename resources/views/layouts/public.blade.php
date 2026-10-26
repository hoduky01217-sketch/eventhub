<!DOCTYPE html>
<html lang="vi" class="h-full scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'EventHub - Khám phá & Đăng ký sự kiện')</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-full flex flex-col bg-slate-50 text-slate-800 font-sans antialiased selection:bg-blue-500 selection:text-white">

    <!-- Header / Navbar (Luma Glassmorphism) -->
    <header x-data="{ mobileMenuOpen: false, userDropdownOpen: false }"
            class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-200/60 transition-all duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">

                <!-- Logo -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('home') }}" class="group flex items-center gap-2.5">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-600 via-blue-500 to-sky-500 flex items-center justify-center text-white shadow-md shadow-blue-500/20 group-hover:scale-105 transition-transform">
                            <x-icons.ticket class="w-5 h-5" />
                        </div>
                        <span class="text-2xl font-black tracking-tight text-slate-900 group-hover:text-blue-600 transition-colors">
                            Event<span class="text-blue-600">Hub</span>
                        </span>
                    </a>
                </div>

                <!-- Desktop Navigation Links -->
                <nav class="hidden md:flex items-center gap-1.5 bg-slate-100/70 p-1.5 rounded-full border border-slate-200/50">
                    <a href="{{ route('home') }}"
                       class="px-4 py-2 rounded-full text-sm font-semibold transition {{ request()->routeIs('home') ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60' }}">
                        Khám phá sự kiện
                    </a>

                    @auth
                        @if(Auth::user()->role === 'user')
                            <a href="{{ route('registrations.index') }}"
                               class="px-4 py-2 rounded-full text-sm font-semibold transition {{ request()->routeIs('registrations.*') ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60' }}">
                                Sự kiện của tôi
                            </a>
                        @endif

                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}"
                               class="px-4 py-2 rounded-full text-sm font-semibold transition {{ request()->routeIs('admin.*') ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60' }}">
                                Quản trị
                            </a>
                        @endif
                    @endauth
                </nav>

                <!-- Desktop User / Auth Area -->
                <div class="hidden md:flex items-center gap-3">
                    @auth
                        <!-- User Dropdown Menu -->
                        <div class="relative" @click.outside="userDropdownOpen = false">
                            <button @click="userDropdownOpen = !userDropdownOpen"
                                    type="button"
                                    class="flex items-center gap-2.5 p-1.5 pr-3 rounded-full hover:bg-slate-100/80 border border-transparent hover:border-slate-200/60 transition">
                                <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-sm">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <span class="text-sm font-semibold text-slate-800 max-w-[120px] truncate">
                                    {{ Auth::user()->name }}
                                </span>
                                <svg class="w-4 h-4 text-slate-400 transition-transform duration-200"
                                     :class="{ 'rotate-180': userDropdownOpen }"
                                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- Dropdown Box -->
                            <div x-show="userDropdownOpen"
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-100"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl shadow-slate-200/60 border border-slate-100 py-2 z-50"
                                 style="display: none;">
                                
                                <div class="px-4 py-2 border-b border-slate-100">
                                    <p class="text-xs text-slate-400">Đăng nhập với tư cách</p>
                                    <p class="text-sm font-bold text-slate-800 truncate">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-slate-500 truncate">{{ Auth::user()->email }}</p>
                                </div>

                                @if(Auth::user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}"
                                       class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 hover:text-blue-600 font-medium">
                                        <x-icons.tag class="w-4 h-4 text-slate-400" />
                                        Trang quản trị (Admin)
                                    </a>
                                @else
                                    <a href="{{ route('registrations.index') }}"
                                       class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 hover:text-blue-600 font-medium">
                                        <x-icons.ticket class="w-4 h-4 text-slate-400" />
                                        Sự kiện của tôi
                                    </a>
                                @endif

                                <a href="{{ route('profile.edit') }}"
                                   class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 hover:text-blue-600 font-medium">
                                    <x-icons.users class="w-4 h-4 text-slate-400" />
                                    Hồ sơ tài khoản
                                </a>

                                <div class="border-t border-slate-100 my-1"></div>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                            class="w-full flex items-center gap-2.5 px-4 py-2 text-sm text-rose-600 hover:bg-rose-50 font-medium transition text-left">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        Đăng xuất
                                    </button>
                                </form>
                            </div>
                        </div>

                    @else
                        <!-- Guest Buttons -->
                        <a href="{{ route('login') }}"
                           class="px-4 py-2 text-sm font-semibold text-slate-600 hover:text-slate-900 transition">
                            Đăng nhập
                        </a>

                        <a href="{{ route('register') }}"
                           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-sm font-semibold bg-slate-900 text-white hover:bg-blue-600 transition shadow-sm hover:shadow-blue-500/20 active:scale-95">
                            <span>Đăng ký</span>
                            <x-icons.arrow-right class="w-3.5 h-3.5" />
                        </a>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <div class="flex items-center md:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen"
                            type="button"
                            class="p-2 rounded-xl text-slate-600 hover:bg-slate-100 transition focus:outline-none"
                            aria-label="Toggle menu">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                            <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" style="display: none;" />
                        </svg>
                    </button>
                </div>

            </div>
        </div>

        <!-- Mobile Dropdown -->
        <div x-show="mobileMenuOpen"
             x-transition
             class="md:hidden border-t border-slate-100 bg-white px-4 pt-3 pb-5 space-y-2 shadow-lg"
             style="display: none;">
            <a href="{{ route('home') }}"
               class="block px-3 py-2.5 rounded-xl text-base font-semibold {{ request()->routeIs('home') ? 'bg-blue-50 text-blue-600' : 'text-slate-700 hover:bg-slate-50' }}">
                Khám phá sự kiện
            </a>

            @auth
                @if(Auth::user()->role === 'user')
                    <a href="{{ route('registrations.index') }}"
                       class="block px-3 py-2.5 rounded-xl text-base font-semibold {{ request()->routeIs('registrations.*') ? 'bg-blue-50 text-blue-600' : 'text-slate-700 hover:bg-slate-50' }}">
                        Sự kiện của tôi
                    </a>
                @endif

                @if(Auth::user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}"
                       class="block px-3 py-2.5 rounded-xl text-base font-semibold {{ request()->routeIs('admin.*') ? 'bg-blue-50 text-blue-600' : 'text-slate-700 hover:bg-slate-50' }}">
                        Trang quản trị (Admin)
                    </a>
                @endif

                <a href="{{ route('profile.edit') }}"
                   class="block px-3 py-2.5 rounded-xl text-base font-medium text-slate-600 hover:bg-slate-50">
                    Hồ sơ tài khoản ({{ Auth::user()->name }})
                </a>

                <form method="POST" action="{{ route('logout') }}" class="pt-2">
                    @csrf
                    <button type="submit"
                            class="w-full text-left px-3 py-2.5 rounded-xl text-base font-medium text-rose-600 hover:bg-rose-50">
                        Đăng xuất
                    </button>
                </form>
            @else
                <div class="pt-3 border-t border-slate-100 flex flex-col gap-2">
                    <a href="{{ route('login') }}"
                       class="w-full text-center py-2.5 rounded-xl text-sm font-semibold text-slate-700 border border-slate-200 hover:bg-slate-50">
                        Đăng nhập
                    </a>
                    <a href="{{ route('register') }}"
                       class="w-full text-center py-2.5 rounded-xl text-sm font-semibold bg-blue-600 text-white hover:bg-blue-700 shadow-sm">
                        Đăng ký tài khoản
                    </a>
                </div>
            @endauth
        </div>
    </header>

    <!-- Floating Global Flash Messages -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        @if(session('success'))
            <div x-data="{ show: true }"
                 x-show="show"
                 x-transition
                 class="mt-5 flex items-center justify-between p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                        <x-icons.check class="w-4 h-4" />
                    </div>
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                </div>
                <button @click="show = false" class="text-emerald-500 hover:text-emerald-700 p-1">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div x-data="{ show: true }"
                 x-show="show"
                 x-transition
                 class="mt-5 flex items-center justify-between p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <p class="text-sm font-medium">{{ session('error') }}</p>
                </div>
                <button @click="show = false" class="text-rose-500 hover:text-rose-700 p-1">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Modern Minimalist Footer (Luma Style) -->
    <footer class="mt-20 border-t border-slate-200/80 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10">

                <!-- Col 1: Brand Info -->
                <div class="md:col-span-2 space-y-4">
                    <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-blue-600 to-sky-500 flex items-center justify-center text-white shadow-sm">
                            <x-icons.ticket class="w-4 h-4" />
                        </div>
                        <span class="text-xl font-black tracking-tight text-slate-900">
                            Event<span class="text-blue-600">Hub</span>
                        </span>
                    </a>
                    <p class="text-sm text-slate-500 max-w-sm leading-relaxed">
                        Nền tảng khám phá, theo dõi và đăng ký tham gia các sự kiện, hội thảo công nghệ và workshop chuyên nghiệp hàng đầu.
                    </p>
                    <div class="flex items-center gap-3 pt-2">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            Hệ thống hoạt động ổn định
                        </span>
                    </div>
                </div>

                <!-- Col 2: Liên kết nhanh -->
                <div class="space-y-3">
                    <h4 class="text-xs font-bold tracking-wider uppercase text-slate-400">Khám phá</h4>
                    <ul class="space-y-2.5 text-sm font-medium text-slate-600">
                        <li>
                            <a href="{{ route('home') }}" class="hover:text-blue-600 transition">Tất cả sự kiện</a>
                        </li>
                        <li>
                            <a href="{{ route('home', ['period' => 'upcoming']) }}" class="hover:text-blue-600 transition">Sự kiện sắp tới</a>
                        </li>
                        <li>
                            <a href="{{ route('home', ['period' => 'available']) }}" class="hover:text-blue-600 transition">Đang mở đăng ký</a>
                        </li>
                    </ul>
                </div>

                <!-- Col 3: Dự án đồ án -->
                <div class="space-y-3">
                    <h4 class="text-xs font-bold tracking-wider uppercase text-slate-400">Thông tin đồ án</h4>
                    <ul class="space-y-2.5 text-sm text-slate-600">
                        <li class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                            <span>Lập trình mã nguồn mở</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                            <span>Laravel 12 & Tailwind CSS</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                            <span>Thiết kế UI: Luma Minimalist</span>
                        </li>
                    </ul>
                </div>

            </div>

            <!-- Bottom Copyright Bar -->
            <div class="mt-12 pt-8 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500">
                <p>&copy; {{ date('Y') }} EventHub. Mọi quyền được bảo lưu.</p>
                <p class="flex items-center gap-1">
                    Thiết kế tinh gọn & hiện đại cho trải nghiệm người dùng tối ưu.
                </p>
            </div>
        </div>
    </footer>

</body>
</html>

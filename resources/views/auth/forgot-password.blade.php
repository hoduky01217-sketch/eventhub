<x-guest-layout>

    <div class="mb-7">
        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
            Quên mật khẩu? 🔑
        </h1>
        <p class="text-sm text-slate-500 mt-2">
            Đừng lo lắng! Hãy nhập email bạn đã đăng ký, EventHub sẽ gửi liên kết để bạn đặt lại mật khẩu mới.
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-5" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                Địa chỉ Email đã đăng ký
            </label>
            <div class="relative flex items-center">
                <div class="absolute left-3.5 text-slate-400 pointer-events-none">
                    <x-icons.mail class="w-4 h-4" />
                </div>
                <input id="email"
                       type="email"
                       name="email"
                       value="{{ old('email') }}"
                       required
                       autofocus
                       placeholder="example@domain.com"
                       class="w-full pl-10 pr-4 py-3 text-sm bg-slate-50 hover:bg-slate-100/70 focus:bg-white rounded-2xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition placeholder:text-slate-400 font-medium @error('email') border-rose-300 bg-rose-50/30 @enderror">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <div class="pt-2">
            <button type="submit"
                    class="w-full inline-flex items-center justify-center gap-2 py-3.5 px-5 rounded-2xl text-sm font-bold bg-gradient-to-r from-blue-600 to-blue-600 text-white hover:from-blue-700 hover:to-blue-700 shadow-md shadow-blue-500/20 active:scale-95 transition cursor-pointer">
                <span>Gửi liên kết đặt lại mật khẩu</span>
                <x-icons.arrow-right class="w-4 h-4" />
            </button>
        </div>

        <div class="text-center pt-4 border-t border-slate-100 text-xs text-slate-500">
            <a href="{{ route('login') }}"
               class="inline-flex items-center gap-1.5 font-bold text-slate-600 hover:text-blue-600 transition">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Quay lại trang Đăng nhập</span>
            </a>
        </div>
    </form>

</x-guest-layout>

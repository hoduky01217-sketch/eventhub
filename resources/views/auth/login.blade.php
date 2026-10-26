<x-guest-layout>

    <!-- Header Section of Form -->
    <div class="mb-6">
        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
            Đăng nhập
        </h1>
        <p class="text-xs sm:text-sm text-slate-500 mt-1.5">
            Nhập thông tin tài khoản của bạn để tiếp tục.
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                Địa chỉ Email
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
                       autocomplete="username"
                       placeholder="example@domain.com"
                       class="w-full pl-10 pr-4 py-3 text-sm bg-slate-50 hover:bg-slate-100/70 focus:bg-white rounded-2xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition placeholder:text-slate-400 font-medium @error('email') border-rose-300 bg-rose-50/30 @enderror">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <!-- Password with Show/Hide toggle -->
        <div x-data="{ showPass: false }">
            <div class="flex items-center justify-between mb-1.5">
                <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                    Mật khẩu
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                       class="text-xs font-semibold text-blue-600 hover:text-blue-700 hover:underline">
                        Quên mật khẩu?
                    </a>
                @endif
            </div>

            <div class="relative flex items-center">
                <div class="absolute left-3.5 text-slate-400 pointer-events-none">
                    <x-icons.lock class="w-4 h-4" />
                </div>
                <input id="password"
                       :type="showPass ? 'text' : 'password'"
                       name="password"
                       required
                       autocomplete="current-password"
                       placeholder="••••••••"
                       class="w-full pl-10 pr-11 py-3 text-sm bg-slate-50 hover:bg-slate-100/70 focus:bg-white rounded-2xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition placeholder:text-slate-400 font-medium @error('password') border-rose-300 bg-rose-50/30 @enderror">

                <button type="button"
                        @click="showPass = !showPass"
                        class="absolute right-3.5 text-slate-400 hover:text-slate-600 p-1 focus:outline-none">
                    <span x-show="!showPass"><x-icons.eye class="w-4 h-4" /></span>
                    <span x-show="showPass" style="display: none;"><x-icons.eye-slash class="w-4 h-4" /></span>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <!-- Remember Me Checkbox -->
        <div class="flex items-center justify-between pt-0.5">
            <label for="remember_me" class="inline-flex items-center gap-2 cursor-pointer">
                <input id="remember_me"
                       type="checkbox"
                       name="remember"
                       class="w-4 h-4 rounded-md border-slate-300 text-blue-600 shadow-sm focus:ring-blue-500">
                <span class="text-xs font-medium text-slate-600">Ghi nhớ mật khẩu</span>
            </label>
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <button type="submit"
                    class="w-full inline-flex items-center justify-center gap-2 py-3.5 px-5 rounded-2xl text-sm font-bold bg-gradient-to-r from-blue-600 to-blue-600 text-white hover:from-blue-700 hover:to-blue-700 shadow-md shadow-blue-500/20 active:scale-95 transition cursor-pointer">
                <span>Đăng nhập</span>
                <x-icons.arrow-right class="w-4 h-4" />
            </button>
        </div>

        <!-- Switch to Register -->
        <div class="text-center pt-4 border-t border-slate-100 text-xs text-slate-500">
            Bạn chưa có tài khoản?
            <a href="{{ route('register') }}"
               class="font-bold text-blue-600 hover:text-blue-700 hover:underline ml-1">
                Đăng ký ngay
            </a>
        </div>

    </form>

</x-guest-layout>

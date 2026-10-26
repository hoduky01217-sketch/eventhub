<x-guest-layout>

    <!-- Header Section of Form -->
    <div class="mb-6">
        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
            Đăng ký tài khoản
        </h1>
        <p class="text-xs sm:text-sm text-slate-500 mt-1.5">
            Tạo tài khoản để tham gia và nhận vé các sự kiện.
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                Họ và tên
            </label>
            <div class="relative flex items-center">
                <div class="absolute left-3.5 text-slate-400 pointer-events-none">
                    <x-icons.user class="w-4 h-4" />
                </div>
                <input id="name"
                       type="text"
                       name="name"
                       value="{{ old('name') }}"
                       required
                       autofocus
                       autocomplete="name"
                       placeholder="Nguyễn Văn A"
                       class="w-full pl-10 pr-4 py-2.5 sm:py-3 text-sm bg-slate-50 hover:bg-slate-100/70 focus:bg-white rounded-2xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition placeholder:text-slate-400 font-medium @error('name') border-rose-300 bg-rose-50/30 @enderror">
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-1.5" />
        </div>

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
                       autocomplete="username"
                       placeholder="example@domain.com"
                       class="w-full pl-10 pr-4 py-2.5 sm:py-3 text-sm bg-slate-50 hover:bg-slate-100/70 focus:bg-white rounded-2xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition placeholder:text-slate-400 font-medium @error('email') border-rose-300 bg-rose-50/30 @enderror">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <!-- Password with Show/Hide toggle -->
        <div x-data="{ showPass: false }">
            <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                Mật khẩu
            </label>
            <div class="relative flex items-center">
                <div class="absolute left-3.5 text-slate-400 pointer-events-none">
                    <x-icons.lock class="w-4 h-4" />
                </div>
                <input id="password"
                       :type="showPass ? 'text' : 'password'"
                       name="password"
                       required
                       autocomplete="new-password"
                       placeholder="Tối thiểu 8 ký tự"
                       class="w-full pl-10 pr-11 py-2.5 sm:py-3 text-sm bg-slate-50 hover:bg-slate-100/70 focus:bg-white rounded-2xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition placeholder:text-slate-400 font-medium @error('password') border-rose-300 bg-rose-50/30 @enderror">

                <button type="button"
                        @click="showPass = !showPass"
                        class="absolute right-3.5 text-slate-400 hover:text-slate-600 p-1 focus:outline-none">
                    <span x-show="!showPass"><x-icons.eye class="w-4 h-4" /></span>
                    <span x-show="showPass" style="display: none;"><x-icons.eye-slash class="w-4 h-4" /></span>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <!-- Confirm Password with Show/Hide toggle -->
        <div x-data="{ showConfirmPass: false }">
            <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                Xác nhận mật khẩu
            </label>
            <div class="relative flex items-center">
                <div class="absolute left-3.5 text-slate-400 pointer-events-none">
                    <x-icons.lock class="w-4 h-4" />
                </div>
                <input id="password_confirmation"
                       :type="showConfirmPass ? 'text' : 'password'"
                       name="password_confirmation"
                       required
                       autocomplete="new-password"
                       placeholder="Nhập lại mật khẩu"
                       class="w-full pl-10 pr-11 py-2.5 sm:py-3 text-sm bg-slate-50 hover:bg-slate-100/70 focus:bg-white rounded-2xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition placeholder:text-slate-400 font-medium @error('password_confirmation') border-rose-300 bg-rose-50/30 @enderror">

                <button type="button"
                        @click="showConfirmPass = !showConfirmPass"
                        class="absolute right-3.5 text-slate-400 hover:text-slate-600 p-1 focus:outline-none">
                    <span x-show="!showConfirmPass"><x-icons.eye class="w-4 h-4" /></span>
                    <span x-show="showConfirmPass" style="display: none;"><x-icons.eye-slash class="w-4 h-4" /></span>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5" />
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <button type="submit"
                    class="w-full inline-flex items-center justify-center gap-2 py-3.5 px-5 rounded-2xl text-sm font-bold bg-gradient-to-r from-blue-600 to-blue-600 text-white hover:from-blue-700 hover:to-blue-700 shadow-md shadow-blue-500/20 active:scale-95 transition cursor-pointer">
                <span>Tạo tài khoản</span>
                <x-icons.arrow-right class="w-4 h-4" />
            </button>
        </div>

        <!-- Switch to Login -->
        <div class="text-center pt-4 border-t border-slate-100 text-xs text-slate-500">
            Bạn đã có tài khoản?
            <a href="{{ route('login') }}"
               class="font-bold text-blue-600 hover:text-blue-700 hover:underline ml-1">
                Đăng nhập ngay
            </a>
        </div>

    </form>

</x-guest-layout>

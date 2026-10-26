
@extends('layouts.public')

@section('title', $event->title . ' - EventHub')

@section('content')

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <a href="{{ route('home') }}"
       class="text-blue-600 hover:underline">
        ← Quay lại danh sách sự kiện
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-6">

        <!-- Thông tin sự kiện -->
        <div class="lg:col-span-2">

            <div class="bg-white rounded-2xl border shadow-sm overflow-hidden">

                @if($event->cover_image)
                    <img src="{{ asset('storage/' . $event->cover_image) }}"
                         alt="{{ $event->title }}"
                         class="w-full h-80 object-cover">
                @endif

                <div class="p-6">

                    <span class="bg-blue-50 text-blue-700
                                 px-3 py-1 rounded-full text-sm">
                        {{ $event->category->name }}
                    </span>

                    <h1 class="text-3xl font-extrabold mt-4 mb-6">
                        {{ $event->title }}
                    </h1>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-8">

                        <div>
                            <p class="text-sm text-gray-500">Bắt đầu</p>
                            <p class="font-semibold">
                                {{ $event->starts_at->format('d/m/Y H:i') }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">Kết thúc</p>
                            <p class="font-semibold">
                                {{ $event->ends_at->format('d/m/Y H:i') }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">Địa điểm</p>
                            <p class="font-semibold">
                                {{ $event->location }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">Hạn đăng ký</p>
                            <p class="font-semibold">
                                {{ $event->registration_deadline->format('d/m/Y H:i') }}
                            </p>
                        </div>

                    </div>

                    <h2 class="text-xl font-bold mb-3">
                        Giới thiệu sự kiện
                    </h2>

                    <div class="text-gray-700 leading-8 whitespace-pre-line">{{ $event->description }}</div>

                </div>
            </div>
        </div>

        <!-- Đăng ký tham gia -->
        <div>
            <div class="bg-white rounded-2xl border shadow-sm p-6 sticky top-28">

                <h2 class="text-xl font-bold mb-5">
                    Đăng ký tham gia
                </h2>

                <div class="mb-5">
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-gray-500">Đã đăng ký</span>
                        <strong>
                            {{ $event->registered_count }}/{{ $event->capacity }}
                        </strong>
                    </div>

                    <div class="h-3 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-blue-600 rounded-full"
                             style="width: {{ min(100, $event->capacity > 0 ? $event->registered_count / $event->capacity * 100 : 0) }}%">
                        </div>
                    </div>

                    <p class="text-sm text-gray-500 mt-2">
                        Còn {{ $remaining }} chỗ trống
                    </p>
                </div>

                @if($errors->any())
                    <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-4">
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                @guest
                    <p class="text-sm text-gray-500 mb-4">
                        Bạn cần đăng nhập để tham gia sự kiện.
                    </p>

                    <a href="{{ route('login') }}"
                       class="block text-center bg-blue-600
                              text-white py-3 rounded-lg hover:bg-blue-700">
                        Đăng nhập để đăng ký
                    </a>

                @else
                    @if(Auth::user()->isAdmin())

                        <div class="bg-gray-100 p-4 rounded-lg text-sm">
                            Bạn đang sử dụng tài khoản Admin.
                            Hãy dùng tài khoản User để đăng ký.
                        </div>

                    @elseif($registration && $registration->status === 'registered')

                        <div class="bg-green-100 text-green-800
                                    p-4 rounded-lg mb-4">
                            Bạn đã đăng ký sự kiện này!
                        </div>

                        @if(
                            !$registration->checked_in_at &&
                            now()->lessThan($event->starts_at)
                        )
                            <form method="POST"
                                  action="{{ route('registrations.destroy', $registration) }}"
                                  onsubmit="return confirm('Bạn muốn hủy đăng ký?')">

                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="w-full border border-red-500
                                               text-red-600 py-3 rounded-lg
                                               hover:bg-red-50">
                                    Hủy đăng ký
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('registrations.index') }}"
                           class="block text-center text-blue-600
                                  mt-4 hover:underline">
                            Xem các sự kiện của tôi
                        </a>

                    @elseif($canRegister)

                        <form method="POST"
                              action="{{ route('registrations.store', $event) }}">

                            @csrf

                            <button type="submit"
                                    class="w-full bg-blue-600 text-white
                                           py-3 rounded-lg font-semibold
                                           hover:bg-blue-700 transition">
                                {{ $registration ? 'Đăng ký lại' : 'Đăng ký tham gia ngay' }}
                            </button>
                        </form>

                    @else

                        <div class="bg-amber-50 text-amber-800
                                    p-4 rounded-lg text-sm">
                            @if(now()->greaterThanOrEqualTo($event->starts_at))
                                Sự kiện đã bắt đầu hoặc kết thúc.
                            @elseif(now()->greaterThan($event->registration_deadline))
                                Đã hết hạn đăng ký.
                            @elseif($remaining <= 0)
                                Sự kiện đã đủ người tham gia.
                            @else
                                Hiện không thể đăng ký sự kiện.
                            @endif
                        </div>

                    @endif
                @endguest

            </div>
        </div>

    </div>
</div>

@endsection


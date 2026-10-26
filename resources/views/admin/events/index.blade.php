<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-gray-800">
            Quản lý sự kiện
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="bg-white rounded-xl shadow p-6">

                <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
                    <h3 class="text-lg font-semibold">
                        Danh sách sự kiện
                    </h3>

                    <a href="{{ route('admin.events.create') }}"
                       class="bg-blue-600 hover:bg-blue-700
                              text-white px-4 py-2 rounded-lg transition">
                        + Thêm sự kiện
                    </a>
                </div>

                @if(session('success'))
                    <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
                <form method="GET"
                      action="{{ route('admin.events.index') }}"
                      class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-6">

                    <input type="text"
                           name="q"
                           value="{{ request('q') }}"
                           placeholder="Tìm tên sự kiện..."
                           class="rounded-lg border-gray-300">

                    <select name="category_id"
                            class="rounded-lg border-gray-300">
                        <option value="">Tất cả chuyên mục</option>

                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                @selected(request('category_id') == $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>

                    <select name="status"
                            class="rounded-lg border-gray-300">
                        <option value="">Tất cả trạng thái</option>
                        <option value="draft" @selected(request('status') === 'draft')>
                            Bản nháp
                        </option>
                        <option value="published" @selected(request('status') === 'published')>
                            Đã công bố
                        </option>
                        <option value="cancelled" @selected(request('status') === 'cancelled')>
                            Đã hủy
                        </option>
                    </select>

                    <button class="bg-gray-800 text-white px-4 py-2 rounded-lg">
                        Lọc sự kiện
                    </button>
                </form>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="p-3 text-left">Sự kiện</th>
                                <th class="p-3 text-left">Chuyên mục</th>
                                <th class="p-3 text-left">Bắt đầu</th>
                                <th class="p-3 text-left">Đăng ký</th>
                                <th class="p-3 text-left">Trạng thái</th>
                                <th class="p-3 text-left">Thao tác</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($events as $event)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="p-3">
                                        <div class="flex items-center gap-3">
                                            @if($event->cover_image)
                                                <img src="{{ asset('storage/' . $event->cover_image) }}"
                                                     class="w-16 h-12 object-cover rounded"
                                                     alt="Ảnh sự kiện">
                                            @endif

                                            <div>
                                                <p class="font-semibold">
                                                    {{ $event->title }}
                                                </p>
                                                <p class="text-xs text-gray-500">
                                                    {{ $event->location }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="p-3">
                                        {{ $event->category->name }}
                                    </td>

                                    <td class="p-3 whitespace-nowrap">
                                        {{ $event->starts_at->format('d/m/Y H:i') }}
                                    </td>

                                    <td class="p-3">
                                        {{ $event->registered_count }}/{{ $event->capacity }}
                                    </td>

                                    <td class="p-3">
                                        @if($event->status === 'published')
                                            <span class="text-green-600">Công bố</span>
                                        @elseif($event->status === 'cancelled')
                                            <span class="text-red-600">Đã hủy</span>
                                        @else
                                            <span class="text-amber-600">Bản nháp</span>
                                        @endif
                                    </td>

                                    <td class="p-3">
                                        <div class="flex gap-3 items-center">
                                            <a href="{{ route('admin.events.edit', $event) }}"
                                               class="text-blue-600 hover:underline">
                                                Sửa
                                            </a>

                                            <form method="POST"
                                                  action="{{ route('admin.events.destroy', $event) }}"
                                                  onsubmit="return confirm('Bạn chắc chắn muốn xóa sự kiện?')">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="text-red-600 hover:underline">
                                                    Xóa
                                                </button>
                                            </form>

                                            <a href="{{ route('admin.events.registrations', $event) }}"
                                                class="text-blue-600 hover:underline">
                                                Người tham gia
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6"
                                        class="p-6 text-center text-gray-500">
                                        Chưa có sự kiện nào.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $events->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>


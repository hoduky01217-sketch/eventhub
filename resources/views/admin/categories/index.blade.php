
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-gray-800">
            Quản lý chuyên mục
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">

            <div class="bg-white rounded-xl shadow p-6">

                <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
    <h3 class="text-lg font-semibold">
        Danh sách chuyên mục
    </h3>

    <a href="{{ route('admin.categories.create') }}"
       class="inline-flex items-center bg-blue-600
              hover:bg-blue-700 text-white
              px-4 py-2 rounded-lg transition">
        + Thêm chuyên mục
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

                <form method="GET"
                      action="{{ route('admin.categories.index') }}"
                      class="flex gap-2 mb-5">

                    <input type="text"
                           name="q"
                           value="{{ request('q') }}"
                           placeholder="Tìm tên chuyên mục..."
                           class="rounded-lg border-gray-300 flex-1">

                    <button class="bg-gray-800 text-white px-4 rounded-lg">
                        Tìm kiếm
                    </button>
                </form>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="p-3 text-left">ID</th>
                                <th class="p-3 text-left">Tên chuyên mục</th>
                                <th class="p-3 text-left">Số sự kiện</th>
                                <th class="p-3 text-left">Trạng thái</th>
                                <th class="p-3 text-left">Thao tác</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($categories as $category)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="p-3">{{ $category->id }}</td>

                                    <td class="p-3 font-medium">
                                        {{ $category->name }}
                                    </td>

                                    <td class="p-3">
                                        {{ $category->events_count }}
                                    </td>

                                    <td class="p-3">
                                        @if($category->is_active)
                                            <span class="text-green-600">
                                                Hoạt động
                                            </span>
                                        @else
                                            <span class="text-gray-500">
                                                Đã ẩn
                                            </span>
                                        @endif
                                    </td>

                                    <td class="p-3">
                                        <div class="flex items-center gap-3">
                                            <a href="{{ route('admin.categories.edit', $category) }}"
                                               class="text-blue-600 hover:underline">
                                                Sửa
                                            </a>

                                            <form method="POST"
                                                  action="{{ route('admin.categories.destroy', $category) }}"
                                                  onsubmit="return confirm('Bạn chắc chắn muốn xóa?')">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="text-red-600 hover:underline">
                                                    Xóa
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5"
                                        class="p-6 text-center text-gray-500">
                                        Chưa có chuyên mục nào.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-5">
                    {{ $categories->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>


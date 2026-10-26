
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold">
            {{ $category->exists ? 'Sửa chuyên mục' : 'Thêm chuyên mục' }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4">

            <div class="bg-white shadow rounded-xl p-6">

                <form method="POST"
                      action="{{ $category->exists
                          ? route('admin.categories.update', $category)
                          : route('admin.categories.store') }}">

                    @csrf

                    @if($category->exists)
                        @method('PUT')
                    @endif

                    <div class="mb-5">
                        <label class="block font-medium mb-2">
                            Tên chuyên mục
                        </label>

                        <input type="text"
                               name="name"
                               required
                               value="{{ old('name', $category->name) }}"
                               class="w-full rounded-lg border-gray-300">

                        @error('name')
                            <p class="text-red-600 text-sm mt-1">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="mb-5">
                        <label class="block font-medium mb-2">
                            Mô tả
                        </label>

                        <textarea name="description"
                                  rows="4"
                                  class="w-full rounded-lg border-gray-300">{{ old('description', $category->description) }}</textarea>

                        @error('description')
                            <p class="text-red-600 text-sm mt-1">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block font-medium mb-2">
                            Trạng thái
                        </label>

                        <select name="is_active"
                                class="w-full rounded-lg border-gray-300">
                            <option value="1"
                                @selected(old('is_active', (int)$category->is_active) == 1)>
                                Hoạt động
                            </option>

                            <option value="0"
                                @selected(old('is_active', (int)$category->is_active) == 0)>
                                Ẩn
                            </option>
                        </select>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700
                                       text-white px-5 py-2 rounded-lg">
                            {{ $category->exists ? 'Cập nhật' : 'Lưu chuyên mục' }}
                        </button>

                        <a href="{{ route('admin.categories.index') }}"
                           class="bg-gray-200 px-5 py-2 rounded-lg">
                            Quay lại
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>


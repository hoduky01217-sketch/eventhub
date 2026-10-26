
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-gray-800">
            {{ $event->exists ? 'Chỉnh sửa sự kiện' : 'Thêm sự kiện mới' }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4">
            <div class="bg-white rounded-xl shadow p-6">

                <form method="POST"
                      enctype="multipart/form-data"
                      action="{{ $event->exists
                          ? route('admin.events.update', $event)
                          : route('admin.events.store') }}">

                    @csrf

                    @if($event->exists)
                        @method('PUT')
                    @endif

                    @if($errors->any())
                        <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-5">
                            <p class="font-semibold mb-2">
                                Vui lòng kiểm tra lại dữ liệu:
                            </p>
                            <ul class="list-disc ml-5">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        <div class="md:col-span-2">
                            <label class="block font-medium mb-2">
                                Tên sự kiện *
                            </label>
                            <input type="text"
                                   name="title"
                                   required
                                   value="{{ old('title', $event->title) }}"
                                   class="w-full rounded-lg border-gray-300">
                        </div>

                        <div>
                            <label class="block font-medium mb-2">
                                Chuyên mục *
                            </label>
                            <select name="category_id"
                                    required
                                    class="w-full rounded-lg border-gray-300">

                                <option value="">-- Chọn chuyên mục --</option>

                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                        @selected(old('category_id', $event->category_id) == $category->id)>
                                        {{ $category->name }}
                                        {{ $category->is_active ? '' : '(Đã ẩn)' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block font-medium mb-2">
                                Địa điểm *
                            </label>
                            <input type="text"
                                   name="location"
                                   required
                                   value="{{ old('location', $event->location) }}"
                                   class="w-full rounded-lg border-gray-300">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block font-medium mb-2">
                                Mô tả sự kiện *
                            </label>
                            <textarea name="description"
                                      rows="5"
                                      required
                                      class="w-full rounded-lg border-gray-300">{{ old('description', $event->description) }}</textarea>
                        </div>

                        <div>
                            <label class="block font-medium mb-2">
                                Thời gian bắt đầu *
                            </label>
                            <input type="datetime-local"
                                   name="starts_at"
                                   required
                                   value="{{ old('starts_at', $event->starts_at?->format('Y-m-d\TH:i')) }}"
                                   class="w-full rounded-lg border-gray-300">
                        </div>

                        <div>
                            <label class="block font-medium mb-2">
                                Thời gian kết thúc *
                            </label>
                            <input type="datetime-local"
                                   name="ends_at"
                                   required
                                   value="{{ old('ends_at', $event->ends_at?->format('Y-m-d\TH:i')) }}"
                                   class="w-full rounded-lg border-gray-300">
                        </div>

                        <div>
                            <label class="block font-medium mb-2">
                                Hạn đăng ký *
                            </label>
                            <input type="datetime-local"
                                   name="registration_deadline"
                                   required
                                   value="{{ old('registration_deadline', $event->registration_deadline?->format('Y-m-d\TH:i')) }}"
                                   class="w-full rounded-lg border-gray-300">
                        </div>

                        <div>
                            <label class="block font-medium mb-2">
                                Số lượng tối đa *
                            </label>
                            <input type="number"
                                   name="capacity"
                                   required
                                   min="1"
                                   value="{{ old('capacity', $event->capacity) }}"
                                   class="w-full rounded-lg border-gray-300">
                        </div>

                        <div>
                            <label class="block font-medium mb-2">
                                Trạng thái *
                            </label>
                            <select name="status"
                                    class="w-full rounded-lg border-gray-300">
                                <option value="draft"
                                    @selected(old('status', $event->status) === 'draft')>
                                    Bản nháp
                                </option>

                                <option value="published"
                                    @selected(old('status', $event->status) === 'published')>
                                    Công bố
                                </option>

                                <option value="cancelled"
                                    @selected(old('status', $event->status) === 'cancelled')>
                                    Hủy sự kiện
                                </option>
                            </select>
                        </div>

                        <div x-data="{ imageUrl: null }"
                             class="md:col-span-2">

                            <label class="block font-medium mb-2">
                                Ảnh đại diện (JPG, PNG, WEBP – tối đa 2MB)
                            </label>

                            <input type="file"
                                   name="cover_image"
                                   accept=".jpg,.jpeg,.png,.webp"
                                   x-on:change="
                                     if ($event.target.files.length) {
                                         imageUrl = URL.createObjectURL($event.target.files[0])
                                     }
                                   "
                                   class="block w-full text-sm">

                            <img x-show="imageUrl"
                                 x-bind:src="imageUrl"
                                 alt="Xem trước ảnh"
                                 class="mt-4 w-64 h-40 object-cover rounded-lg">

                            @if($event->cover_image)
                                <div x-show="!imageUrl" class="mt-4">
                                    <p class="text-sm text-gray-500 mb-2">
                                        Ảnh hiện tại:
                                    </p>
                                    <img src="{{ asset('storage/' . $event->cover_image) }}"
                                         class="w-64 h-40 object-cover rounded-lg"
                                         alt="Ảnh sự kiện">
                                </div>
                            @endif
                        </div>

                    </div>

                    <div class="mt-8 flex flex-wrap gap-3">
                        <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700
                                       text-white px-6 py-3 rounded-lg transition">
                            {{ $event->exists ? 'Cập nhật sự kiện' : 'Tạo sự kiện' }}
                        </button>

                        <a href="{{ route('admin.events.index') }}"
                           class="bg-gray-200 hover:bg-gray-300
                                  px-6 py-3 rounded-lg transition">
                            Quay lại
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>



<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-gray-800">
            Check-in sự kiện bằng QR Code
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Khu vực quét QR -->
                <div class="lg:col-span-2 bg-white rounded-2xl
                            shadow p-6">

                    <h3 class="text-xl font-bold mb-2">
                        Quét vé tham gia
                    </h3>

                    <p class="text-gray-500 text-sm mb-5">
                        Đưa QR Code của người tham gia vào camera.
                    </p>

                    <!-- Camera -->
                    <div class="bg-gray-900 rounded-xl overflow-hidden">
                        <video id="qr-video"
                               class="w-full max-h-96 object-contain"
                               playsinline muted></video>
                    </div>

                    <div class="flex flex-wrap gap-3 mt-5">

                        <button id="start-camera" type="button"
                                class="bg-blue-600 hover:bg-blue-700
                                       text-white px-5 py-3 rounded-lg">
                            Bật camera
                        </button>

                        <button id="stop-camera" type="button"
                                class="bg-gray-200 hover:bg-gray-300
                                       px-5 py-3 rounded-lg">
                            Tắt camera
                        </button>
                    </div>

                    <!-- Hiển thị kết quả -->
                    <div id="scan-result"
                         role="status"
                         aria-live="polite"
                         class="mt-6 p-4 rounded-lg bg-gray-100 text-gray-700">
                        Chưa quét mã QR.
                    </div>

                    <!-- Nhập mã thủ công -->
                    <div class="border-t mt-8 pt-6">

                        <h4 class="font-bold mb-3">
                            Nhập mã vé thủ công
                        </h4>

                        <form id="checkin-form"
                              data-url="{{ route('admin.checkin.store') }}">

                            <label for="token-input"
                                   class="block text-sm text-gray-600 mb-2">
                                Mã vé gồm 64 ký tự
                            </label>

                            <input type="text"
                                   id="token-input"
                                   maxlength="64"
                                   autocomplete="off"
                                   placeholder="Nhập mã QR Token..."
                                   class="w-full rounded-lg border-gray-300">

                            <button type="submit"
                                    class="mt-3 bg-emerald-600
                                           hover:bg-emerald-700 text-white
                                           px-5 py-3 rounded-lg">
                                Xác nhận check-in
                            </button>

                        </form>
                    </div>

                </div>

                <!-- Thống kê -->
                <div class="bg-white rounded-2xl shadow p-6 h-fit">

                    <h3 class="text-lg font-bold mb-5">
                        Thống kê check-in
                    </h3>

                    <div class="bg-green-50 rounded-xl p-5 text-center">
                        <p class="text-sm text-green-700">
                            Tổng lượt đã check-in
                        </p>

                        <p class="text-4xl font-extrabold
                                  text-green-700 mt-2">
                            {{ $checkedInCount }}
                        </p>
                    </div>

                    <div class="mt-6 text-sm text-gray-500">
                        Dữ liệu được cập nhật khi tải lại trang.
                    </div>

                    <button type="button"
                            onclick="window.location.reload()"
                            class="mt-4 w-full border rounded-lg py-2
                                   hover:bg-gray-50">
                        Làm mới thống kê
                    </button>
                </div>

            </div>

            <!-- Danh sách check-in gần đây -->
            <div class="bg-white rounded-2xl shadow p-6 mt-6">

                <h3 class="text-lg font-bold mb-5">
                    Những lượt check-in gần đây
                </h3>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="p-3 text-left">Người tham gia</th>
                                <th class="p-3 text-left">Sự kiện</th>
                                <th class="p-3 text-left">Thời gian</th>
                                <th class="p-3 text-left">Trạng thái</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($recentCheckIns as $registration)
                                <tr class="border-b">
                                    <td class="p-3">
                                        {{ $registration->user->name }}
                                    </td>

                                    <td class="p-3">
                                        {{ $registration->event->title }}
                                    </td>

                                    <td class="p-3">
                                        {{ $registration->checked_in_at->format('d/m/Y H:i:s') }}
                                    </td>

                                    <td class="p-3">
                                        <span class="text-green-600 font-medium">
                                            Thành công
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4"
                                        class="p-6 text-center text-gray-500">
                                        Chưa có lượt check-in nào.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>

        </div>
    </div>

    @vite('resources/js/checkin.js')
</x-app-layout>

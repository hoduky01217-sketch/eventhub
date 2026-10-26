
<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">
                Người tham gia sự kiện
            </h2>
            <p class="text-sm text-slate-500 mt-1">
                {{ $event->title }}
            </p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">

            <a href="{{ route('admin.events.index') }}"
               class="text-blue-600 hover:underline text-sm">
                ← Quay lại quản lý sự kiện
            </a>

            <!-- Thống kê -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-6 mb-7">

                <div class="bg-white rounded-2xl p-6 border shadow-sm">
                    <p class="text-slate-500 text-sm">Đã đăng ký</p>
                    <p class="text-3xl font-black text-blue-600 mt-2">
                        {{ $summary['registered'] }}
                    </p>
                </div>

                <div class="bg-white rounded-2xl p-6 border shadow-sm">
                    <p class="text-slate-500 text-sm">Đã check-in</p>
                    <p class="text-3xl font-black text-emerald-600 mt-2">
                        {{ $summary['checkedIn'] }}
                    </p>
                </div>

                <div class="bg-white rounded-2xl p-6 border shadow-sm">
                    <p class="text-slate-500 text-sm">Đã hủy</p>
                    <p class="text-3xl font-black text-rose-600 mt-2">
                        {{ $summary['cancelled'] }}
                    </p>
                </div>
            </div>

            <!-- Danh sách -->
            <div class="bg-white border rounded-2xl shadow-sm p-6">

                <div class="flex flex-wrap items-center
                            justify-between gap-4 mb-6">

                    <h3 class="text-lg font-bold">
                        Danh sách người tham gia
                    </h3>

                    <form method="GET">
                        <select name="status"
                                onchange="this.form.submit()"
                                class="rounded-lg border-slate-300 text-sm">

                            <option value="">Tất cả trạng thái</option>

                            <option value="registered"
                                @selected(request('status') === 'registered')>
                                Đã đăng ký
                            </option>

                            <option value="cancelled"
                                @selected(request('status') === 'cancelled')>
                                Đã hủy
                            </option>
                        </select>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="p-3 text-left">Người tham gia</th>
                                <th class="p-3 text-left">Email</th>
                                <th class="p-3 text-left">Ngày đăng ký</th>
                                <th class="p-3 text-left">Trạng thái</th>
                                <th class="p-3 text-left">Check-in</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse($registrations as $registration)
                                <tr class="hover:bg-slate-50">

                                    <td class="p-3 font-semibold">
                                        {{ $registration->user->name }}
                                    </td>

                                    <td class="p-3">
                                        {{ $registration->user->email }}
                                    </td>

                                    <td class="p-3">
                                        {{ $registration->registered_at->format('d/m/Y H:i') }}
                                    </td>

                                    <td class="p-3">
                                        @if($registration->status === 'registered')
                                            <span class="text-emerald-600">
                                                Đã đăng ký
                                            </span>
                                        @else
                                            <span class="text-rose-600">
                                                Đã hủy
                                            </span>
                                        @endif
                                    </td>

                                    <td class="p-3">
                                        @if($registration->checked_in_at)
                                            <span class="text-blue-600">
                                                {{ $registration->checked_in_at->format('d/m/Y H:i') }}
                                            </span>
                                        @else
                                            <span class="text-slate-400">
                                                Chưa check-in
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5"
                                        class="p-8 text-center text-slate-500">
                                        Chưa có người tham gia.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $registrations->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\EventController as PublicEventController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\Admin\CheckInController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\RegistrationController as AdminRegistrationController;

// Trang chủ Laravel tạm thời
Route::get('/', [
    PublicEventController::class, 'index'
])->name('home');

// Dashboard mặc định của Breeze

Route::get('/dashboard', function () {

    // Nếu là Admin, chuyển đến trang quản trị
    if (auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }

    // Nếu là User, chuyển đến trang chủ EventHub
    return redirect()->route('home');

})->middleware(['auth'])->name('dashboard');


// Quản lý hồ sơ tài khoản
Route::middleware('auth')->group(function () {
    Route::get('/profile', [
        ProfileController::class, 'edit'
    ])->name('profile.edit');

    Route::patch('/profile', [
        ProfileController::class, 'update'
    ])->name('profile.update');

    Route::delete('/profile', [
        ProfileController::class, 'destroy'
    ])->name('profile.destroy');

    Route::get('/registrations/{registration}/ticket', [
        TicketController::class, 'show'
    ])->name('tickets.show');
});

// Các đường dẫn dành riêng cho Admin
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard Admin tạm thời
        Route::get('/dashboard', [
            DashboardController::class, 'index'
        ])->name('dashboard');

        // CRUD chuyên mục
        Route::resource('categories', CategoryController::class)
            ->except(['show']);

        // CRUD sự kiện
        Route::resource('events', AdminEventController::class)
            ->except(['show']);

        Route::get('/check-in', [
            CheckInController::class, 'index'
        ])->name('checkin.index');

        Route::post('/check-in', [
            CheckInController::class, 'store'
        ])->middleware('throttle:30,1')
        ->name('checkin.store');

        Route::get('/events/{event}/registrations', [
            AdminRegistrationController::class, 'index'
        ])->name('events.registrations');
        
    });


// Chi tiết sự kiện, không yêu cầu đăng nhập
Route::get('/events/{event:slug}', [
    PublicEventController::class, 'show'
])->name('events.show');

// Các chức năng dành cho tài khoản đã đăng nhập
Route::middleware('auth')->group(function () {

    // Danh sách sự kiện đã đăng ký
    Route::get('/my-events', [
        RegistrationController::class, 'index'
    ])->name('registrations.index');

    // Đăng ký tham gia
    Route::post('/events/{event}/register', [
        RegistrationController::class, 'store'
    ])->middleware('throttle:10,1')
      ->name('registrations.store');

    // Hủy đăng ký
    Route::delete('/registrations/{registration}', [
        RegistrationController::class, 'destroy'
    ])->name('registrations.destroy');

});

// Đường dẫn xem trước 3 phương án UI Admin Dashboard
Route::get('/admin-preview', function () {
    return response()->file(public_path('admin-dashboard-preview.html'));
})->name('admin.preview');

require __DIR__.'/auth.php';
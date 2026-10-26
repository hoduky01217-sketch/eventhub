<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();

            // Người dùng đăng ký sự kiện
            $table->foreignId('event_id')
                ->constrained('events')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Trạng thái đăng ký
            $table->string('status', 20)
                ->default('registered');

            // Mã QR của vé
            $table->string('qr_token', 64)->unique();

            // Thời gian đăng ký
            $table->dateTime('registered_at')->useCurrent();

            // Thời gian hủy đăng ký
            $table->dateTime('cancelled_at')->nullable();

            // Thời gian check-in
            $table->dateTime('checked_in_at')->nullable();

            // Admin thực hiện check-in
            $table->foreignId('checked_in_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            // Một User chỉ có một bản ghi cho mỗi sự kiện
            $table->unique(['event_id', 'user_id']);

            $table->index(['event_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
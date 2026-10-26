<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();

            // Quan hệ với chuyên mục
            $table->foreignId('category_id')
                ->constrained('categories')
                ->restrictOnDelete();

            // Tài khoản Admin tạo sự kiện
            $table->foreignId('created_by')
                ->constrained('users')
                ->restrictOnDelete();

            // Thông tin sự kiện
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('description');
            $table->string('location');
            $table->string('cover_image')->nullable();

            // Thời gian
            $table->dateTime('starts_at');
            $table->dateTime('ends_at');
            $table->dateTime('registration_deadline');

            // Số lượng và trạng thái
            $table->unsignedInteger('capacity');
            $table->string('status', 20)->default('draft');

            $table->timestamps();

            $table->index(['status', 'starts_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('event', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Tiêu đề sự kiện
            $table->string('slug')->unique(); // Slug duy nhất
            $table->text('summary')->nullable(); // Tóm tắt
            $table->longText('description')->nullable(); // Mô tả chi tiết
            $table->json('resources')->nullable(); // Tài nguyên dưới dạng JSON
            $table->dateTime('timestart'); // Thời gian bắt đầu
            $table->dateTime('timeend'); // Thời gian kết thúc
            $table->foreignId('event_type_id') // Loại sự kiện
                ->constrained('event_type') // Bảng `event_types`
                ->onDelete('cascade'); // Xóa sự kiện khi xóa loại sự kiện
            $table->json('tags')->nullable(); // Thẻ dưới dạng JSON
            $table->timestamps(); // Thêm cột created_at và updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event');
    }
};

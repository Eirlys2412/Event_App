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
        Schema::create('thoi_khoa_bieus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('phancong_id')->constrained('phancong')->onDelete('cascade'); // Liên kết với bảng phân công
            $table->enum('buoi', ['Sáng', 'Chiều', 'Tối']); // Buổi học (Sáng/Chiều/Tối)
            $table->date('ngay'); // Ngày học
            $table->integer('tietdau'); // Tiết bắt đầu
            $table->integer('tietcuoi'); // Tiết kết thúc
            $table->timestamps();
            $table->foreignId('diadiem_id')->constrained('dia_diem')->onDelete('cascade'); // Định nghĩa khóa ngoại      
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thoi_khoa_bieus');
    }
};
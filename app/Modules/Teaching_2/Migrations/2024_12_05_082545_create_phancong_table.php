<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('phancong', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('giangvien_id')->nullable(); // Khóa ngoại tới bảng teacher
            $table->unsignedBigInteger('hocphan_id'); // Khóa ngoại tới bảng modules
            $table->unsignedBigInteger('hocky_id'); // Khóa ngoại tới bảng hoc_ky
            $table->unsignedBigInteger('namhoc_id'); // Khóa ngoại tới bảng nam_hoc
            $table->date('ngayphancong'); // Ngày phân công
            $table->date('time_start')->nullable(); // Thời gian bắt đầu (nullable)
            $table->date('time_end')->nullable(); // Thời gian kết thúc (nullable)
            $table->timestamps();

            // Định nghĩa các khóa ngoại
            $table->foreign('giangvien_id')->references('id')->on('teacher')->onDelete('cascade'); // Giảng viên có thể là null
            $table->foreign('hocphan_id')->references('id')->on('hoc_phans')->onDelete('cascade'); // Học phần
            $table->foreign('hocky_id')->references('id')->on('hoc_ky')->onDelete('cascade'); // Học kỳ
            $table->foreign('namhoc_id')->references('id')->on('nam_hoc')->onDelete('cascade'); // Năm học
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('phancong');
    }
};

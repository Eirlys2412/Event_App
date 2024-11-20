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
        Schema::create('teacher', function (Blueprint $table) {
            $table->id();
            $table->string('mgv'); // Mã giảng viên
            $table->unsignedBigInteger('ma_donvi'); // Khóa ngoại đến bảng donvi
            $table->unsignedBigInteger('user_id'); // Khóa ngoại đến bảng users
            $table->unsignedBigInteger('chuyen_nganh'); // Khóa ngoại đến bảng chuyen_nganh
            $table->string('hoc_ham')->nullable();
            $table->string('hoc_vi')->nullable();
            $table->string('loai_giangvien')->nullable();
            $table->timestamps();

            // Khai báo các khóa ngoại
            $table->foreign('ma_donvi')->references('id')->on('donvi')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('chuyen_nganh')->references('id')->on('chuyennganhs')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('teacher');
    }
};

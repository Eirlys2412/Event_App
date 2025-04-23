<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('class_name');
            $table->unsignedBigInteger('teacher_id'); // Đảm bảo kiểu dữ liệu phù hợp
            $table->unsignedBigInteger('nganh_id'); // Đảm bảo kiểu dữ liệu phù hợp
            $table->foreign('teacher_id')->references('id')->on('teacher')->onDelete('cascade'); // Giảng viên có thể là null
            $table->foreign('nganh_id')->references('id')->on('nganh')->onDelete('cascade'); // Giảng viên có thể là null
            $table->text('description')->nullable();
            $table->integer('max_students')->default(30);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('classes');
    }
};

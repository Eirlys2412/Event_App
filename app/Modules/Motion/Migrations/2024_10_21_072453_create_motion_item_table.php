<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('motionitem', function (Blueprint $table) {
            $table->id(); // Khóa chính tự động tăng
            $table->unsignedBigInteger('id_motion'); // Khóa ngoại tham chiếu đến bảng motion
            $table->string('item_code'); // Mã định danh cho từng mục
            $table->integer('count'); // Số lượng sử dụng

            // Định nghĩa khóa ngoại
            $table->foreign('id_motion')->references('id')->on('motion')->onDelete('cascade');
            $table->timestamps(); // Thời gian tạo và cập nhật
        });
    }

    public function down()
    {
        Schema::dropIfExists('motionitem'); // Xóa bảng khi rollback
    }
};

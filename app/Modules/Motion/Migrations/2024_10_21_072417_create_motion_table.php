<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('motion', function (Blueprint $table) {
            $table->id(); // Khóa chính tự động tăng
            $table->string('title'); // Tiêu đề cảm xúc
            $table->string('icon'); // Biểu tượng cảm xúc
            $table->timestamps(); // Thời gian tạo và cập nhật
        });
    }

    public function down()
    {
        Schema::dropIfExists('motion'); // Xóa bảng khi rollback
    }
};

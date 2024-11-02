<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNganhTable extends Migration
{
    public function up()
    {
        Schema::create('nganh', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->unsignedBigInteger('donvi_id'); // Đảm bảo kiểu dữ liệu phù hợp
            $table->string('code')->unique();
            $table->text('content');
            $table->enum('status', ['active', 'inactive'])->default('active'); // Cột status với giá trị mặc định
            $table->timestamps();

            // Ràng buộc khóa ngoại cho donvi_id
            $table->foreign('donvi_id')->references('id')->on('donvi')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('nganh') ;
    }
}
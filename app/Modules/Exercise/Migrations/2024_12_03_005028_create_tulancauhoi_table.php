<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTulancauhoiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tulancauhoi', function (Blueprint $table) {
            $table->id(); // Tạo cột id
            $table->text('content'); // Cột chứa nội dung câu hỏi
            $table->unsignedBigInteger('hocphan_id'); // Khóa ngoại tới bảng modules
            $table->unsignedBigInteger('user_id'); // Khóa ngoại tới bảng users
            $table->json('tags')->nullable(); // Cột chứa tags dưới dạng JSON
            $table->json('resources')->nullable(); // Cột chứa resources dưới dạng JSON
            $table->timestamps(); // Tạo cột created_at và updated_at

            // Ràng buộc khóa ngoại
            $table->foreign('hocphan_id')->references('id')->on('modules')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tulancauhoi');
    }
}
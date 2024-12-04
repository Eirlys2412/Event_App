<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagTuluancauhoiTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tag_tuluancauhoi', function (Blueprint $table) {
            $table->id(); // Tạo cột id
            $table->unsignedBigInteger('tag_id'); // Khóa ngoại tới bảng tags
            $table->unsignedBigInteger('tuluancauhoi_id'); // Khóa ngoại tới bảng tulancauhoi
            $table->timestamps(); // Tạo cột created_at và updated_at

            // Ràng buộc khóa ngoại
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade'); // Khóa ngoại tới bảng tags
            $table->foreign('tuluancauhoi_id')->references('id')->on('tulancauhoi')->onDelete('cascade'); // Khóa ngoại tới bảng tulancauhoi
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tag_tuluancauhoi'); // Xóa bảng nếu tồn tại
    }
}
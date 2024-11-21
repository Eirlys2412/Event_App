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
        Schema::create('chuong_trinh_dao_tao', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('nganh_id'); // Liên kết với bảng ngành
            $table->string('title'); // Tiêu đề
            $table->text('content'); // Nội dung
            $table->unsignedBigInteger('user_id')->nullable(); // Liên kết với bảng users
            $table->integer('tong_tin_chi'); // Tổng số tín chỉ
            $table->enum('status', ['active', 'inactive'])->default('active'); // Trạng thái
            $table->timestamps();

            // Ràng buộc khóa ngoại
            $table->foreign('nganh_id')->references('id')->on('nganh')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chuong_trinh_dao_tao');
    }
};

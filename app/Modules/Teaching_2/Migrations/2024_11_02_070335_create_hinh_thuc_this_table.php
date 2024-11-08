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
    Schema::create('hinh_thuc_this', function (Blueprint $table) {
        $table->id();
        $table->string('title'); // Sửa lại để đúng cách định nghĩa cột title
        $table->boolean('status')->default(0); // Đặt giá trị mặc định là 0
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hinhthucthis');
    }
};

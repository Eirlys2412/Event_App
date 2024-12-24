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
        Schema::create('nam_hoc', function (Blueprint $table) {
            $table->id();
            $table->integer('nam_hoc'); // Lưu năm học dưới dạng chuỗi, ví dụ "2023-2024"
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nam_hoc');
    }
};

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
        Schema::create('trac_nghiem_loais', function (Blueprint $table) {
            $table->id(); // Khóa chính
            $table->string('title'); // Cột title
            $table->boolean('status')->default(0); // Đặt giá trị mặc định là 0
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trac_nghiem_loais');
    }
};

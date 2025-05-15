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
        Schema::create('enroll_certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Liên kết với bảng TimeTable
            $table->string('ketqua');
            $table->foreignId('nguoicap_id')->constrained('teacher')->onDelete('cascade'); // Liên kết với bảng TimeTable
            $table->foreignId('donvi_id')->constrained('donvi')->onDelete('cascade'); // Liên kết với bảng TimeTable
            $table->foreignId('phancong_id')->constrained('phancong')->onDelete('cascade'); // Liên kết với bảng TimeTable
            $table->foreignId('enroll_id')->constrained('enrollments')->onDelete('cascade'); // Liên kết với bảng TimeTable
            $table->foreignId('loai_id')->constrained('loai_chungchi')->onDelete('cascade'); // Liên kết với bảng TimeTable
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enroll_certificates');
    }
};

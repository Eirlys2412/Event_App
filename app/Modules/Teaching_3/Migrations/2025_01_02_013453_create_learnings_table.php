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
        Schema::create('learnings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Liên kết với bảng TimeTable
            $table->foreignId('phancong_id')->constrained('phancong')->onDelete('cascade'); // Liên kết với bảng TimeTable
            $table->integer('noidung_id');
            $table->integer('time_spending'); // Thời gian học (phút)
            $table->enum('status', ['started', 'done'])->default('started'); // Trạng thái
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learnings');
    }
};

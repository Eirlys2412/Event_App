<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnrollmentsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id')->constrained('students')->onDelete('cascade');
            $table->unsignedBigInteger('phancong_id')->constrained('phancong')->onDelete('cascade');
            $table->integer('timespending')->default(0); // Thời gian đã học
            $table->integer('process')->default(0); // % hoàn thành khóa học
            $table->enum('status', ['pending', 'success', 'finished', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
}

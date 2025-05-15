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
        Schema::create('tag_tracnghiemcauhois', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tag_id')->constrained('tags')->onDelete('cascade'); // Định nghĩa khóa ngoại      
            $table->foreignId('tracnghiemcauhoi_id')->constrained('trac_nghiem_cauhois')->onDelete('cascade'); // Định nghĩa khóa ngoại      
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tag_tracnghiemcauhois');
    }
};

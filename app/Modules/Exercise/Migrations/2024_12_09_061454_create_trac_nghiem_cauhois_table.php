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
        Schema::create('trac_nghiem_cauhois', function (Blueprint $table) {
            $table->id();
            $table->string('content');
            // $table->integer('hocphan_id');
            $table->foreignId('hocphan_id')->constrained('hoc_phans')->onDelete('cascade'); // Định nghĩa khóa ngoại      
            $table->string('tags')->nullable(); // Cho phép giá trị null
            $table->string('resources')->nullable(); // Cho phép giá trị null
            // $table->integer('loai_id');
            $table->foreignId('loai_id')->constrained('trac_nghiem_loais')->onDelete('cascade'); // Định nghĩa khóa ngoại      
            // $table->integer('user_id');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Định nghĩa khóa ngoại      
            $table->timestamps();

            // $table->foreign('nganh_id')->references('id')->on('nganh')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trac_nghiem_cauhois');
    }
};

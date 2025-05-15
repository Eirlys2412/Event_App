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
        Schema::create('tu_luan_cauhois', function (Blueprint $table) {
            $table->id();
            $table->string('content');
            $table->foreignId('hocphan_id')->constrained('hoc_phans')->onDelete('cascade'); // Định nghĩa khóa ngoại      
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Định nghĩa khóa ngoại      
            $table->string('tags')->nullable(); // Cho phép giá trị null
            $table->string('resources')->nullable(); // Cho phép giá trị null
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tu_luan_cauhois');
    }
};

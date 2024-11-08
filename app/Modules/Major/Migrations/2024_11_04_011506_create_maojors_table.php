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
        Schema::create('maojors', function (Blueprint $table) {
            $table->bigIncrements('id');         // Khóa chính
            $table->string('title');             // Tên chuyên ngành
            $table->string('slug')->unique();    // Slug chuyên ngành
            $table->boolean('status')->default(1); // Trạng thái chuyên ngành (mặc định là 1 - hoạt động)
            $table->timestamps();       
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maojors');
    }
};

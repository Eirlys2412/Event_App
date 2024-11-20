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
        Schema::create('eventtype', function (Blueprint $table) {
            $table->bigIncrements('id');         // Khóa chính
            $table->string('title');             // Tên sự kiện
            $table->string('slug')->unique();  
            $table->string('description'); 
            $table->boolean('status')->default('1'); 
            $table->timestamps();       
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eventtype');
    }
};

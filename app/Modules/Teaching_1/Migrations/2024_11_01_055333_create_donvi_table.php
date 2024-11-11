<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up()
    {
        Schema::create('donvi', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Tên đơn vị
            $table->string('slug')->unique(); // Slug của đơn vị (duy nhất)
            $table->unsignedBigInteger('parent_id')->nullable(); // Khóa ngoại đến đơn vị cha
            $table->json('children_id')->nullable(); // Các đơn vị con dưới dạng JSON
            $table->timestamps();

            // Khai báo khóa ngoại cho parent_id
            $table->foreign('parent_id')->references('id')->on('donvi')->onDelete('cascade');
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('donvi');
    }
};

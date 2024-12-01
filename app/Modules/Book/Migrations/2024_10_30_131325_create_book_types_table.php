<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookTypesTable extends Migration
{
    public function up()
    {
        Schema::create('book_types', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();
            $table->string('slug')->unique();
            $table->enum('status',['active','inactive'])->default('active');
            $table->timestamps();  
        });
    }

    public function down()
    {
        Schema::dropIfExists('book_types');
    }
}


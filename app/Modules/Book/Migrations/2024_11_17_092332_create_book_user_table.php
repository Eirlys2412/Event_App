<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookUserTable extends Migration
{
    public function up()
    {
        Schema::create('book_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');  
            $table->integer('points');             
            $table->timestamps();

            // Tạo khóa ngoại
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('book_users');
    }
}

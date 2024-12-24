<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBodeTuLuansTable extends Migration
{
    public function up()
    {
        Schema::create('bode_tuluans', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedBigInteger('hocphan_id');
            $table->string('slug')->unique();
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
            $table->integer('time'); // Duration in minutes
            $table->string('tags')->nullable(); // Comma-separated tags
            $table->unsignedBigInteger('user_id');
            $table->float('total_points');
            $table->json('questions'); // JSON list of questions
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('hocphan_id')->references('id')->on('hoc_phans')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('bode_tuluans');
    }
}

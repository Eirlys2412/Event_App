<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('votable_type');
            $table->unsignedBigInteger('votable_id');
            $table->tinyInteger('rating')->unsigned(); // 1-5 stars
            $table->timestamps();
            $table->unique(['user_id','votable_id', 'votable_type']);
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('votes');
    }
}; 
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
        Schema::create('event_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('event_id')->constrained('event')->onDelete('cascade');
            $table->integer('vote_count')->default(0);// số lượng vote
            $table->integer('vote_score')->default(0);// điểm vote
            $table->timestamps();

            $table->unique(['event_id', 'user_id']);
        });

        Schema::create('event_user_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voter_id')->constrained('users')->onDelete('cascade');// người vote
            $table->foreignId('event_user_id')->constrained('event_user')->onDelete('cascade');
            $table->integer('score');//
            $table->text('comment')->nullable();//
            $table->timestamps();

            $table->unique(['voter_id', 'event_user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_user_votes');//
        Schema::dropIfExists('event_user');
    }
};

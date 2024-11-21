<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBooksTable extends Migration
{
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('photo')->nullable();
            $table->text('summary')->nullable();
            $table->longText('content')->nullable();
            $table->json('tags')->nullable(); 
            $table->json('resources')->nullable(); 
            $table->enum('status',['active','inactive'])->default('active');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('book_type_id')->nullable();
            $table->foreign('book_type_id')->references('id')->on('book_types')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('books');
        Schema::table('books', function (Blueprint $table) {
            $table->dropForeign(['book_type_id']);
            $table->dropColumn('book_type_id');
        });
    }
}


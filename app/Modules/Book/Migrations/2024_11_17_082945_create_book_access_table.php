<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookAccessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_access', function (Blueprint $table) {
            $table->id(); // Auto increment id
            $table->unsignedBigInteger('book_id'); // book_id to reference book
            $table->integer('point_access'); // point_access to store points
            $table->timestamps(); // created_at and updated_at timestamps

            // Foreign key constraint
            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('book_access');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookPointsTable extends Migration
{
    public function up()
    {
        Schema::create('book_points', function (Blueprint $table) {
            $table->id(); 
            $table->string('func_cmd')->unique(); 
            $table->integer('point');           
            $table->timestamps();           
        });
    }

    public function down()
    {
        Schema::dropIfExists('book_points');
    }
}

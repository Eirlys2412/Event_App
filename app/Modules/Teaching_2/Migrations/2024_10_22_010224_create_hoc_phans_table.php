<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHocPhansTable extends Migration
{
    public function up()
    {
        Schema::create('hoc_phans', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('photo');
            $table->string('code');
            $table->string('content');
            $table->string('summary');
            $table->integer('tinchi');
            $table->string('hinhthucthi');
            $table->integer('user_id')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('hoc_phans');
    }
}
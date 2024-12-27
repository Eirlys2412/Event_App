<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhanconggroupTable extends Migration
{
    public function up()
    {
        Schema::create('phanconggroup', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_id'); // Liên kết đến bảng group
            $table->unsignedBigInteger('phancong_id'); // Liên kết đến bảng phancong
            $table->timestamps();

            // Thiết lập khóa ngoại
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->foreign('phancong_id')->references('id')->on('phancong')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('phanconggroup');
    }
}

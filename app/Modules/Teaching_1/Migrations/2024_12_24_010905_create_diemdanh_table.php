<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiemdanhTable extends Migration
{
    public function up()
    {
        Schema::create('diemdanh', function (Blueprint $table) {
            $table->id('diemdanh_id');
            $table->unsignedBigInteger('sinhvien_id');
            $table->unsignedBigInteger('hocphan_id');
            $table->timestamp('time');
            $table->enum('trangthai', ['có mặt', 'vắng mặt', 'muộn'])->default('vắng mặt');
            $table->timestamps();

            $table->foreign('sinhvien_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('hocphan_id')->references('id')->on('hoc_phans')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('diemdanh');
    }
}


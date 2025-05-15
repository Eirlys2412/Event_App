<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
       * Run the migrations.
       *
       * @return void
       */
      public function up()
      {
          Schema::create('tu_luan_dapans', function (Blueprint $table) {
              $table->id();
              $table->unsignedBigInteger('tu_luan_id'); // Khóa ngoại liên kết tới bảng tracnghiem_cauhois
              $table->string('content'); // Nội dung đáp án
              $table->json('resounce_list')->nullable(); // Danh sách tài nguyên dạng JSON
              $table->timestamps();
  
              // Khóa ngoại
              $table->foreign('tu_luan_id')->references('id')->on('tu_luan_cauhois')->onDelete('cascade');
          });
      }
  
      /**
       * Reverse the migrations.
       *
       * @return void
       */
      public function down()
      {
          Schema::dropIfExists('tu_luan_dapans');
      }
};

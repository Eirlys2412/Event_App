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
          Schema::create('trac_nghiem_dapans', function (Blueprint $table) {
              $table->id();
              $table->unsignedBigInteger('tracnghiem_id'); // Khóa ngoại liên kết tới bảng tracnghiem_cauhois
              $table->string('content'); // Nội dung đáp án
              $table->json('resounce_list')->nullable(); // Danh sách tài nguyên dạng JSON
              $table->boolean('is_correct')->default(false); // Đáp án đúng hoặc sai
              $table->timestamps();
  
              // Khóa ngoại
              $table->foreign('tracnghiem_id')->references('id')->on('trac_nghiem_cauhois')->onDelete('cascade');
          });
      }
  
      /**
       * Reverse the migrations.
       *
       * @return void
       */
      public function down()
      {
          Schema::dropIfExists('trac_nghiem_dapans');
      }
};

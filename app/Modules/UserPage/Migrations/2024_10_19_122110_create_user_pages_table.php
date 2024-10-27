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
        Schema::create('user_pages', function (Blueprint $table) {
            $table->id(); // cột id tự tăng
            $table->string('title'); // cột title kiểu chuỗi
            $table->string('slug')->unique(); // cột slug kiểu chuỗi và là duy nhất
            $table->text('summary')->nullable(); // cột summary kiểu văn bản, có thể để trống
            $table->string('items')->nullable(); // cột items kiểu JSON, có thể để trống
            $table->timestamps(); // cột created_at và updated_at tự động
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_pages');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('phancong', function (Blueprint $table) {
            // $table->string('max_student')->nullable();
            $table->unsignedBigInteger('class_id')->nullable(); // Liên kết với bảng users
            $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('phancong', function (Blueprint $table) {
            // $table->dropColumn('max_student');
            $table->dropColumn('class_id');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('phancong', function (Blueprint $table) {
            $table->string('class_course')->nullable();
            $table->string('max_student')->nullable();
        });
    }

    public function down()
    {
        Schema::table('phancong', function (Blueprint $table) {
            $table->dropColumn('class_course');
            $table->dropColumn('max_student');
        });
    }
};

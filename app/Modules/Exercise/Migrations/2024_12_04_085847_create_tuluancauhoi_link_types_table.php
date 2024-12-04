<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTuluancauhoiLinkTypesTable extends Migration // Đảm bảo tên lớp này không bị trùng
{
    public function up()
    {
        Schema::create('tuluancauhoi_link_types', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('code')->unique();
            $table->string('viewcode')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tuluancauhoi_link_types');
    }
}
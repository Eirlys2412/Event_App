<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResourceLinkTypesTable extends Migration
{
    public function up()
    {
        Schema::create('resource_link_types', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('code')->unique();
            $table->string('viewcode')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('resource_link_types');
    }
}

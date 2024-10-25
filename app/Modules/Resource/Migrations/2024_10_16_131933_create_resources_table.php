<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResourcesTable extends Migration
{
    public function up()
    {
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('file_name')->nullable();
            $table->string('file_type')->nullable();
            $table->string('file_size')->nullable();
            $table->string('path')->nullable();
            $table->string('url')->nullable();
            $table->string('tags')->nullable();
            $table->string('description')->nullable();
            $table->string('youtube_url')->nullable();
            $table->string('document_url')->nullable();
            $table->string('image_url')->nullable();
            $table->unsignedBigInteger('resource_type_id')->nullable()->default(null);
            $table->unsignedBigInteger('resource_link_type_id')->nullable()->default(null);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('resources');
    }
}

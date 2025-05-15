<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('blogs', function (Blueprint $table) {
        $table->json('tags')->nullable();  // Dùng kiểu dữ liệu JSON nếu bạn lưu mảng tag
    });
}

public function down()
{
    Schema::table('blogs', function (Blueprint $table) {
        $table->dropColumn('tags');
    });
}

};

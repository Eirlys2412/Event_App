<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('dia_diem', function (Blueprint $table) {
        $table->id();
        $table->string('title'); // Có thể sử dụng enum nếu cần ràng buộc
        $table->timestamps();
    });

    // Insert dữ liệu mặc định
    DB::table('dia_diem')->insert([
        ['title' => 'Trong nhà', 'created_at' => now(), 'updated_at' => now()],
        ['title' => 'Ngoài trời', 'created_at' => now(), 'updated_at' => now()],
    ]);
}

public function down(): void
{
    Schema::dropIfExists('dia_diem');
}

};

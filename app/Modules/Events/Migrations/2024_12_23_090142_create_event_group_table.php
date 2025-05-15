<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('event_group', function (Blueprint $table) {
            $table->id(); // Khóa chính
            $table->unsignedBigInteger('group_id'); // Khóa ngoại tham chiếu đến bảng groups
            $table->unsignedBigInteger('event_id'); // Khóa ngoại tham chiếu đến bảng event
            $table->timestamps(); // Thêm cột created_at và updated_at

            // Ràng buộc khóa ngoại
            $table->foreign('group_id')
                ->references('id')
                ->on('groups')
                ->onDelete('cascade');

            $table->foreign('event_id')
                ->references('id')
                ->on('event')
                ->onDelete('cascade');

            // Ràng buộc tránh trùng lặp
            $table->unique(['group_id', 'event_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_group');
    }
};

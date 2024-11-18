<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTeacherChuyenNganhForeignKey extends Migration
{
    public function up()
    {
        Schema::table('teacher', function (Blueprint $table) {
            // Bước 1: Xóa khóa ngoại cũ
            $table->dropForeign(['chuyen_nganh']); // Nếu khóa ngoại hiện tại là 'chuyen_nganh'
            $table->dropColumn('chuyen_nganh');

            // Bước 2: Thêm lại cột `chuyen_nganh` với kiểu dữ liệu và khóa ngoại mới
            $table->unsignedBigInteger('chuyen_nganh')->after('user_id'); // Điều chỉnh vị trí cột

            $table->foreign('chuyen_nganh')
                ->references('id')
                ->on('chuyennganhs') // Liên kết lại với bảng `chuyen_nganh`
                ->onDelete('cascade'); // Hoặc onDelete('set null') tùy nhu cầu
        });
    }

    public function down()
    {
        Schema::table('teacher', function (Blueprint $table) {
            // Khôi phục khóa ngoại cũ nếu cần
            $table->dropForeign(['chuyen_nganh']);
            $table->dropColumn('chuyen_nganh');

            $table->unsignedBigInteger('chuyen_nganh');
            $table->foreign('chuyen_nganh')
                ->references('id')
                ->on('chuyennganhs')
                ->onDelete('cascade');
        });
    }
}
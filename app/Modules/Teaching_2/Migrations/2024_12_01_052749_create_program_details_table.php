<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgramDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('program_details', function (Blueprint $table) {
            $table->id(); // Tự động tạo cột ID (Primary Key)

            // Thêm khóa ngoại đến bảng modules
            $table->unsignedBigInteger('hocphan_id'); // Mã học phần
            $table->foreign('hocphan_id') // Tạo khóa ngoại
                  ->references('id')->on('modules') // Liên kết với cột id trong bảng modules
                  ->onDelete('cascade') // Xóa chi tiết nếu module bị xóa
                  ->onUpdate('cascade'); // Cập nhật khóa ngoại khi module thay đổi

            // Thêm khóa ngoại đến bảng chuong_trinh_dao_tao
            $table->unsignedBigInteger('chuongtrinh_id'); // Mã chương trình
            $table->foreign('chuongtrinh_id') // Tạo khóa ngoại
                  ->references('id')->on('chuong_trinh_dao_tao') // Liên kết với cột id trong bảng chuong_trinh_dao_tao
                  ->onDelete('cascade') // Xóa chi tiết nếu chương trình bị xóa
                  ->onUpdate('cascade'); // Cập nhật khóa ngoại khi chương trình thay đổi

            $table->integer('hocky'); // Học kỳ
            $table->string('loai', 50); // Loại (bắt buộc, tự chọn)
            $table->json('hocphantienquyet')->nullable(); // Danh sách học phần tiên quyết (JSON)
            $table->json('hocphansongsong')->nullable(); // Danh sách học phần song song (JSON)
            $table->timestamps(); // Cột created_at và updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('program_details', function (Blueprint $table) {
            $table->dropForeign(['hocphan_id']); // Xóa khóa ngoại hocphan_id
            $table->dropForeign(['chuongtrinh_id']); // Xóa khóa ngoại chuongtrinh_id
        });
        Schema::dropIfExists('program_details');
    }
}
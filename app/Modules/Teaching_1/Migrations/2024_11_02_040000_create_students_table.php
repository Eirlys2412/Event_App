<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('mssv');
            $table->unsignedBigInteger('donvi_id');
            $table->unsignedBigInteger('nganh_id');
            $table->string('khoa');
            $table->enum('status', ['đang học', 'thôi học', 'tốt nghiệp']);
            $table->unsignedBigInteger('user_id');
            $table->string('slug')->unique();
            $table->timestamps();
        
        
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('students');
    }
}

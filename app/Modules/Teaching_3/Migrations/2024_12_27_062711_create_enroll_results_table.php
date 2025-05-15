<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enroll_results', function (Blueprint $table) {
            $table->id(); // Primary key

            $table->unsignedBigInteger('enroll_id'); // Foreign key to enrollments table
            $table->foreign('enroll_id')->references('id')->on('enrollments')->onDelete('cascade');

            $table->unsignedBigInteger('user_id'); // Foreign key to users table
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('hinhthucthi_id'); // Foreign key to hinh_thuc_this table
            $table->foreign('hinhthucthi_id')->references('id')->on('hinh_thuc_this')->onDelete('cascade');

            // Polymorphic relationship for bode
            // $table->string('bode_type'); // To store the type of the bode (bo_de_trac_nghiems or bo_de_tu_luans)
            $table->unsignedBigInteger('bode_id'); // To store the ID of the related bode

            $table->decimal('grade', 5, 2)->nullable(); // Grade, e.g., 95.50

            $table->json('chitiet')->nullable(); // JSON field for detailed answers

            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('enroll_results');
    }
};

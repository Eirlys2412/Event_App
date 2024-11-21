<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('book_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('book_id');
            $table->string('transaction_type', 50);
            $table->integer('points_change');
            $table->timestamp('transaction_date')->useCurrent();

            // Ràng buộc khóa ngoại
            $table->foreign('user_id')->references('user_id')->on('book_users')->onDelete('cascade');
            $table->foreign('book_id')->references('id')->on('book_access')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('book_transactions');
    }
}

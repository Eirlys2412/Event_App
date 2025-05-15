<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('event_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('event_id');
            $table->string('order_id')->unique(); // Mã đơn hàng MoMo
            $table->string('amount');
            $table->string('payment_url');
            $table->string('result_code')->nullable(); // Mã kết quả từ MoMo
            $table->string('message')->nullable(); // Thông điệp trả về từ MoMo
            $table->timestamps();
        });
        
    }

    public function down()
    {
        Schema::dropIfExists('momopay_transactions');
    }
}; 
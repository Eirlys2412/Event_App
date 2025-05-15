<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('vnpay_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('order_id');
            $table->decimal('amount', 10, 2);
            $table->string('order_info');
            $table->string('status')->default('pending');
            $table->string('bank_code')->nullable();
            $table->string('transaction_no')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vnpay_transactions');
    }
}; 
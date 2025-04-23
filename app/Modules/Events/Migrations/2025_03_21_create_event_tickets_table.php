<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('event_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('event');
            $table->foreignId('user_id')->constrained('users');
            $table->integer('quantity');
            $table->string('ticket_type');
            $table->foreignId('transaction_id')->constrained('vnpay_transactions');
            $table->integer('total_amount');
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('event_tickets');
    }
}; 
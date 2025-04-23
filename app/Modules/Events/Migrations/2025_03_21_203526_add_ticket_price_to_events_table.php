<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('event', function (Blueprint $table) {
            $table->integer('ticket_price')->default(0);
            $table->integer('available_tickets')->default(0);
        });
    }

    public function down()
    {
        Schema::table('event', function (Blueprint $table) {
            $table->dropColumn('ticket_price');
            $table->dropColumn('available_tickets');
        });
    }
}; 
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('payer_name');
            $table->string('payer_email');
            $table->decimal('amount', 10, 2);
            $table->string('payment_method')->default('GCash');
            $table->string('status')->default('pending'); // For example: pending, confirmed, failed
            $table->string('transaction_id')->nullable(); // Optional, if you want to save a reference number
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
        Schema::dropIfExists('payments');
    }
}

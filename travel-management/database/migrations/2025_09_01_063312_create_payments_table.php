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
            $table->unsignedBigInteger('user_id')->nullable(); // Optional user relationship
            $table->string('payer_name');
            $table->string('payer_email');
            $table->string('contact', 15);
            $table->decimal('amount', 10, 2);
            $table->string('payment_method')->default('GCash');
            $table->string('status')->default('pending'); // pending, confirmed, etc.
            $table->string('transaction_id')->nullable(); // Optional GCash ref no.
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
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

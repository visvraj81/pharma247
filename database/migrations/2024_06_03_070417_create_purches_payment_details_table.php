<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchesPaymentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purches_payment_details', function (Blueprint $table) {
            $table->id();
            $table->string('distributor_name');
            $table->string('payment_date');
            $table->string('payment_mode');
            $table->string('status');
            $table->string('bill_amount');
            $table->string('paid_amount');
            $table->string('due_amount');
            $table->string('payment_id');
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
        Schema::dropIfExists('purches_payment_details');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchesPaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purches_payment', function (Blueprint $table) {
            $table->id();
            $table->string('distributor')->nullable();
            $table->string('payment_date')->nullable();
            $table->string('payment_mode')->nullable();
            $table->string('note')->nullable();
            $table->string('unused_amount')->nullable();
            $table->string('total')->nullable();
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
        Schema::dropIfExists('purches_payment');
    }
}

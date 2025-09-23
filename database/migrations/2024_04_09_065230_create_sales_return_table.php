<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesReturnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_return', function (Blueprint $table) {
            $table->id();
            $table->string('bill_no')->nullable();
            $table->string('date')->nullable();
            $table->string('name_mobile_no')->nullable();
            $table->string('payment_mode')->nullable();
            $table->string('mrp_total')->nullable();
            $table->string('total_discount')->nullable();
            $table->string('bill_amount')->nullable();
            $table->string('adjustment_amount')->nullable();
            $table->string('round_off')->nullable();
            $table->string('total_gst')->nullable();
            $table->string('cess')->nullable();
            $table->string('margin')->nullable();
            $table->string('net_amount')->nullable();
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
        Schema::dropIfExists('sales_return');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('customer_id')->nullable();
            $table->string('customer_number')->nullable();
            $table->string('doctor_name')->nullable();
            $table->string('total_gst')->nullable();
            $table->string('margin')->nullable();
            $table->string('total_qty')->nullable();
            $table->string('mrp')->nullable();
            $table->string('dicount')->nullable();
            $table->string('extra_charges')->nullable();
            $table->string('adjustment')->nullable();
            $table->string('round_off')->nullable();
            $table->string('net_amt')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales');
    }
}

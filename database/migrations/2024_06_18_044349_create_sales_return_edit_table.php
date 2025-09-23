<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesReturnEditTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_return_edit', function (Blueprint $table) {
            $table->id();
            $table->string('sales_id')->nullable();
            $table->string('iteam_id')->nullable();
            $table->string('user_id')->nullable();
            $table->string('qty')->nullable();
            $table->string('exp')->nullable();
            $table->string('mrp')->nullable();
            $table->string('random_number')->nullable();
            $table->string('gst')->nullable();
            $table->string('net_rate')->nullable();
            $table->string('unit')->nullable();
            $table->string('batch')->nullable();
            $table->string('base')->nullable();
            $table->string('location')->nullable();
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
        Schema::dropIfExists('sales_return_edit');
    }
}

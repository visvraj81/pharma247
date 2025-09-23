<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_details', function (Blueprint $table) {
            $table->id();
            $table->string('sales_id')->nullable();
            $table->string('iteam_id')->nullable();
            $table->string('qty')->nullable();
            $table->string('mgn')->nullable();
            $table->string('exp')->nullable();
            $table->string('discount')->nullable();
            $table->string('gst')->nullable();
            $table->string('mrp')->nullable();
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
        Schema::dropIfExists('sales_details');
    }
}

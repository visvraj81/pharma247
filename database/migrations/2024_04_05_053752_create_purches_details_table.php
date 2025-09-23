<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchesDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purches_details', function (Blueprint $table) {
            $table->id();
            $table->string('purches_id')->nullable();
            $table->string('iteam_id')->nullable();
            $table->string('hsn_code')->nullable();
            $table->string('unit')->nullable();
            $table->string('batch')->nullable();
            $table->string('exp_dt')->nullable();
            $table->string('mrp')->nullable();
            $table->string('qty')->nullable();
            $table->string('fr_qty')->nullable();
            $table->string('ptr')->nullable();
            $table->string('disocunt')->nullable();
            $table->string('d_percent')->nullable();
            $table->string('base')->nullable();
            $table->string('gst')->nullable();
            $table->string('amount')->nullable();
            $table->string('lp')->nullable();
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
        Schema::dropIfExists('purches_details');
    }
}

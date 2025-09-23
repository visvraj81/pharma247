<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinalPurchesItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('final_purches_item', function (Blueprint $table) {
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
            $table->string('location')->nullable();
            $table->string('textable')->nullable();
            $table->string('scheme_account')->nullable();
            $table->string('margin')->nullable();
            $table->string('weightage')->nullable();
            $table->string('user_id')->nullable();
            $table->string('net_rate')->nullable();
            $table->string('random_number')->nullable();
            $table->string('iteam_purches_id')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('final_purches_item');
    }
}

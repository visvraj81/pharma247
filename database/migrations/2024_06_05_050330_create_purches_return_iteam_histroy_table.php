<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchesReturnIteamHistroyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purches_return_iteam_histroy', function (Blueprint $table) {
            $table->id();
            $table->string('item_history_id');
            $table->string('iteam_id');
            $table->string('batch');
            $table->string('exp_dt');
            $table->string('mrp');
            $table->string('qty');
            $table->string('fr_qty');
            $table->string('ptr');
            $table->string('disocunt');
            $table->string('gst');
            $table->string('amount');
            $table->string('location');
            $table->string('weightage');
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
        Schema::dropIfExists('purches_return_iteam_histroy');
    }
}

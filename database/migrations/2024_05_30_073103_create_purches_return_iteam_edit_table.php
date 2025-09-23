<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchesReturnIteamEditTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parches_return_item_edit', function (Blueprint $table) {
            $table->id();
            $table->string('purches_id');
            $table->string('iteam_id');
            $table->string('unit');
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
            $table->string('user_id');
            $table->string('weightage');
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
        Schema::dropIfExists('parches_return_item_edit');
    }
}

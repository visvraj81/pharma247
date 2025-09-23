<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesIteamAddTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_item_add', function (Blueprint $table) {
            $table->id();
            $table->string('random_number');
            $table->string('user_id');
            $table->string('item_id');
            $table->string('qty');
            $table->string('exp');
            $table->string('gst');
            $table->string('mrp');
            $table->string('amt');
            $table->string('unit');
            $table->string('batch');
            $table->string('base');
            $table->string('order');
            $table->string('location');
            $table->string('net_rate');
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
        Schema::dropIfExists('sales_item_add');
    }
}

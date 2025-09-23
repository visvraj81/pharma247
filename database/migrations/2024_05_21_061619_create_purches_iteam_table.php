<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchesIteamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_item', function (Blueprint $table) {
            $table->id();
            $table->string('random_number');
            $table->string('batch_number');
            $table->string('expiry');
            $table->string('mrp');
            $table->string('ptr');
            $table->string('qty');
            $table->string('first_qty');
            $table->string('scheme_account');
            $table->string('discount');
            $table->string('base_price');
            $table->string('gst');
            $table->string('location');
            $table->string('unite');
            $table->string('total_amount');
            $table->string('textable');
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
        Schema::dropIfExists('purchase_item');
    }
}

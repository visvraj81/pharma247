<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBatchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('batch', function (Blueprint $table) {
            $table->id();
            $table->string('item_id')->nullable();
            $table->string('qty')->nullable();
            $table->string('discount')->nullable();
            $table->string('gst')->nullable();
            $table->string('batch_name')->nullable();
            $table->string('expiry_date')->nullable();
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
        Schema::dropIfExists('batch');
    }
}

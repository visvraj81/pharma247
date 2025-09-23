<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('set_pill_reminder', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->nullable();
            $table->string('item_id')->nullable();
            $table->string('reminder_type')->nullable();
            $table->string('time')->nullable();
          	$table->string('user_id')->nullable();
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
        Schema::dropIfExists('set_refill_reminder');
    }
}

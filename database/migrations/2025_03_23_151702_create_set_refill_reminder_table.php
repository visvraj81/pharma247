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
        Schema::create('set_refill_reminder', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->nullable();
            $table->string('item_id')->nullable();
            $table->string('duration')->nullable();
            $table->string('morning')->nullable();
            $table->string('afternoon')->nullable();
            $table->string('night')->nullable();
          	$table->string('user_id')->nullable();
          	$table->string('notification_date')->nullable();
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
        Schema::dropIfExists('set_refill_reminder');
    }
}

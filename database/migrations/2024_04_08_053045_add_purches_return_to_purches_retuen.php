<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPurchesReturnToPurchesRetuen extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purches_retuen', function (Blueprint $table) {
            $table->string('ptr_total')->nullable();
            $table->string('ptr_discount')->nullable();
            $table->string('cess')->nullable();
            $table->string('tcs')->nullable();
            $table->string('extra_charge')->nullable();
            $table->string('adjustment_amoount')->nullable();
            $table->string('round_off')->nullable();
            $table->string('net_amount')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purches_retuen', function (Blueprint $table) {
            //
        });
    }
}

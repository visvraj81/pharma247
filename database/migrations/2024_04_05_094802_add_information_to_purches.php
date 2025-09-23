<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInformationToPurches extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purches', function (Blueprint $table) {
            $table->string('ptr_total');
            $table->string('ptr_discount');
            $table->string('cess');
            $table->string('tcs');
            $table->string('extra_charge');
            $table->string('adjustment_amoount');
            $table->string('round_off');
            $table->string('net_amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purches', function (Blueprint $table) {
            //
        });
    }
}

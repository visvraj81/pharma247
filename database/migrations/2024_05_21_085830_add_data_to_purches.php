<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDataToPurches extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purches', function (Blueprint $table) {
            $table->string('total_gst');
            $table->string('total_amount');
            $table->string('payment_type');
            $table->string('owner_type');
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

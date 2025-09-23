<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AdPurchesToPurchesRetuen extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purches_retuen', function (Blueprint $table) {
            $table->string('sgst')->default('0');
            $table->string('cgst')->default('0');
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

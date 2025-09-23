<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPurchesDetailsToPurchesRetuen extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purches_retuen', function (Blueprint $table) {
            $table->string('start_end_date');
            $table->string('end_end_date');
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

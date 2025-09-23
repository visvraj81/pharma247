<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInformationToIteams extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('iteams', function (Blueprint $table) {
            $table->longText('image')->nullable();
            $table->string('default_disc')->nullable();
            $table->string('loaction')->nullable();
            $table->string('cess')->nullable();
            $table->string('gtin')->nullable();
            $table->string('accept_online_order')->nullable();
            $table->string('manage_type')->nullable();
            $table->string('morning_dose')->nullable();
            $table->string('afternoon_dose')->nullable();
            $table->string('evening_dose')->nullable();
            $table->string('nigte_dose')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('iteams', function (Blueprint $table) {
            //
        });
    }
}

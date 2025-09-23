<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIteamToIteams extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('iteams', function (Blueprint $table) {
            $table->string('prescription_required')->nullable();
            $table->string('fact_box')->nullable();
            $table->string('primary_use')->nullable();
            $table->string('use_of')->nullable();
            $table->string('common_side_effect')->nullable();
            $table->string('alcohol_Interaction')->nullable();
            $table->string('pregnancy_Interaction')->nullable();
            $table->string('lactation_Interaction')->nullable();
            $table->string('driving_Interaction')->nullable();
            $table->string('country_of_origin')->nullable();
            $table->longText('q_a')->nullable();
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

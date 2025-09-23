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
        Schema::create('switch_account', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->nullable();
          	$table->string('switch_user_id')->nullable();
          	$table->string('name')->nullable();
          	$table->string('image')->nullable();
          	$table->string('user_phone_number')->nullable();
          	$table->string('user_password')->nullable();
          	$table->string('password')->nullable();
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
        Schema::dropIfExists('patient_family_relation');
    }
}

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
        Schema::create('patient_order_item', function (Blueprint $table) {
            $table->id();
            $table->string('patient_order_id')->nullable();
            $table->string('iteam_id')->nullable();
            $table->string('price')->nullable();
            $table->string('qty')->nullable();
            $table->string('chemist_id')->nullable();
            $table->string('sub_amount')->nullable();
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
        Schema::dropIfExists('patient_order_item');
    }
}

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
        Schema::create('patient_order', function (Blueprint $table) {
            $table->id();
            $table->string('patient_id')->nullable();
            $table->string('order_id')->nullable();
            $table->string('bill_no')->nullable();
            $table->string('iteam_id')->nullable();
            $table->string('price')->nullable();
            $table->string('qty')->nullable();
            $table->string('chemist_id')->nullable();
            $table->string('address_id')->nullable();
            $table->string('famliy_member_id')->nullable();
            $table->string('delivery_status')->nullable();
            $table->string('round_off')->nullable();
            $table->string('new_amount')->nullable();
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
        Schema::dropIfExists('patient_order');
    }
}

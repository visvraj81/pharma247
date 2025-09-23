<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplierDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_details', function (Blueprint $table) {
            $table->id();
            $table->string('distributer_id')->nullable();
            $table->string('gst')->nullable();
            $table->string('area_number')->nullable();
            $table->string('pincode')->nullable();
            $table->string('opening_balance')->nullable();
            $table->string('pan_no')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('account_no')->nullable();
            $table->string('ifsc_code')->nullable();
            $table->string('food_licence_number')->nullable();
            $table->string('distributer_drug_licence_no')->nullable();
            $table->string('payment_drug_days')->nullable();
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
        Schema::dropIfExists('supplier_details');
    }
}

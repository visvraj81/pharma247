<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesReturnDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_return_details', function (Blueprint $table) {
            $table->id();
            $table->string('sales_id')->nullable();
            $table->string('iteam_id')->nullable();
            $table->string('bill_no')->nullable();
            $table->string('entry_dt')->nullable();
            $table->string('bill_date')->nullable();
            $table->string('entry_by')->nullable();
            $table->string('patient')->nullable();
            $table->string('mobile')->nullable();
            $table->string('amount')->nullable();
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
        Schema::dropIfExists('sales_return_details');
    }
}

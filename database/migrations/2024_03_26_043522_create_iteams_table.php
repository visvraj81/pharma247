<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIteamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('iteams', function (Blueprint $table) {
            $table->id();
            $table->string('iteam_name')->nullable();
            $table->string('unit')->nullable();
            $table->string('pharma_shop')->nullable();
            $table->string('distributer_id')->nullable();
            $table->string('drug_group')->nullable();
            $table->string('gst')->nullable();
            $table->string('packing_type')->nullable();
            $table->string('packing_size')->nullable();
            $table->string('item_type')->nullable();
            $table->string('location')->nullable();
            $table->string('schedule')->nullable();
            $table->string('tax_not_applied')->nullable();
            $table->string('tax')->nullable();
            $table->string('barcode')->nullable();
            $table->string('minimum')->nullable();
            $table->string('maximum')->nullable();
            $table->string('discount')->nullable();
            $table->string('margin')->nullable();
            $table->string('hsn_code')->nullable();
            $table->string('front_photo')->nullable();
            $table->string('back_photo')->nullable();
            $table->string('mrp_photo')->nullable();
            $table->string('message')->nullable();
            $table->string('item_category_id')->nullable();
            $table->string('packaging_id')->nullable();
            $table->string('item_alias')->nullable();
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
        Schema::dropIfExists('iteams');
    }
}

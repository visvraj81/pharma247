<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdjustStockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adjust_stock', function (Blueprint $table) {
            $table->id();
            $table->string('adjustment_date')->nullable();
            $table->string('item_name')->nullable();
            $table->string('batch')->nullable();
            $table->string('company')->nullable();
            $table->string('unite')->nullable();
            $table->string('expriy')->nullable();
            $table->string('mrp')->nullable();
            $table->string('stock')->nullable();
            $table->string('stock_adjust')->nullable();
            $table->string('user_id')->nullable();
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
        Schema::dropIfExists('adjust_stock');
    }
}

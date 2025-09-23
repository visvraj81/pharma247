<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpenseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expense', function (Blueprint $table) {
            $table->id();
            $table->string('category')->nullable();
            $table->string('expense_date')->nullable();
            $table->string('payment_date')->nullable();
            $table->string('gst_type')->nullable();
            $table->string('gst')->nullable();
            $table->string('gstn_number')->nullable();
            $table->string('party')->nullable();
            $table->string('amount')->nullable();
            $table->string('total')->nullable();
            $table->string('payment_mode')->nullable();
            $table->string('reference_no')->nullable();
            $table->string('remark')->nullable();
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
        Schema::dropIfExists('expense');
    }
}

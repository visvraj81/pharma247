<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionPlanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_plan', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('max_product')->nullable();
            $table->string('description')->nullable();
            $table->string('is_popular')->nullable();
            $table->string('monthly_price')->nullable();
            $table->string('annual_price')->nullable();
            $table->string('enable_modules')->nullable();
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
        Schema::dropIfExists('subscription_plan');
    }
}

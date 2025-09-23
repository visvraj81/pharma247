<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePharmaPlanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pharma_plan', function (Blueprint $table) {
            $table->id();
            $table->string('pharma_plan')->nullable();
            $table->string('subscription_plan_id')->nullable();
            $table->string('plan_type')->nullable();
            $table->string('payment_mode')->nullable();
            $table->string('amount')->nullable();
            $table->string('payment_date')->nullable();
            $table->string('license_will_expire_on')->nullable();
            $table->string('next_payment_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pharma_plan');
    }
}

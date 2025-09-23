<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfflineRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offline_requests', function (Blueprint $table) {
            $table->id();
            $table->string('pharma_id');
            $table->string('submitted_by');
            $table->string('subscription_plan');
            $table->string('plan_type');
            $table->string('payment_method');
            $table->string('submitted_on');
            $table->string('status');
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
        Schema::dropIfExists('offline_requests');
    }
}

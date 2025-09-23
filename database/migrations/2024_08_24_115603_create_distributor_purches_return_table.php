<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDistributorPurchesReturnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('distributor_purches_return', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->nullable();
            $table->string('distributor_id')->nullable();
            $table->string('amount')->nullable();
            $table->string('purches_return_bill_id')->nullable();
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
        Schema::dropIfExists('distributor_purches_return');
    }
}

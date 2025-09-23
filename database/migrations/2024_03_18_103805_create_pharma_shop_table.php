<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePharmaShopTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pharma_shop', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->nullable();
            $table->string('pharma_name')->nullable();
            $table->string('pharma_short_name')->nullable();
            $table->string('pharma_email')->nullable();
            $table->string('pharma_phone_number')->nullable();
            $table->string('pharma_timezone')->nullable();
            $table->string('pharma_status')->nullable();
            $table->string('pharma_address')->nullable();
            $table->string('dark_logo')->nullable();
            $table->string('light_logo')->nullable();
            $table->string('small_dark_logo')->nullable();
            $table->string('small_light_logo')->nullable();
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
        Schema::dropIfExists('pharma_shop');
    }
}

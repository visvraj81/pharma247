<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemQrCodeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_qr_code', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->nullable();
            $table->string('item_id')->nullable();
            $table->string('batch_number')->nullable();
            $table->string('qr_code')->nullable();
            $table->string('qr_code_link')->nullable();
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
        Schema::dropIfExists('item_qr_code');
    }
}

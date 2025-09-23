<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePassbookTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('passbook', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('date');
            $table->string('party_name');
            $table->string('bank_id');
            $table->string('deposit');
            $table->string('withdraw');
            $table->string('balance');
            $table->string('mode');
            $table->string('remark');
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
        Schema::dropIfExists('passbook');
    }
}

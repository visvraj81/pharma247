<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddClounmAddToLedger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ledger', function (Blueprint $table) {
            $table->string('bill_date')->nullable();
            $table->string('name')->nullable();
            $table->string('in')->nullable();
            $table->string('out')->nullable();
            $table->string('balance_stock')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ledger', function (Blueprint $table) {
            //
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank', function (Blueprint $table) {
            $table->id();
            $table->string('bank_name');
            $table->string('bank_account_name');
            $table->string('opening_balance');
            $table->string('date');
            $table->string('bank_details_check');
            $table->string('bank_account_number');
            $table->string('reenter_bank_account_number');
            $table->string('ifsc_code');
            $table->string('bank_branch_name');
            $table->string('account_holder_name');
            $table->string('upi_id');
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
        Schema::dropIfExists('bank');
    }
}

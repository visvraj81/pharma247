<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInforToBatch extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('batch', function (Blueprint $table) {
            $table->string('batch_number')->nullable();
            $table->string('ptr')->nullable();
            $table->string('margin')->nullable();
            $table->string('total_mrp')->nullable();
            $table->string('total_ptr')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('batch', function (Blueprint $table) {
            //
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailInqueryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_inquery', function (Blueprint $table) {
            $table->id();
            $table->string('date_time')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('message')->nullable();
            $table->string('replied')->nullable();
            $table->string('subject')->nullable();
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
        Schema::dropIfExists('email_inquery');
    }
}

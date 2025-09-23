<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOnlineOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('online_order', function (Blueprint $table) {
            $table->id();
            $table->string('item_id')->nullable();
            $table->string('item_name')->nullable();
            $table->string('company_name')->nullable();
            $table->string('supplier_name')->nullable();
            $table->string('stock')->nullable();
            $table->string('y_n')->nullable();
            $table->string('user_id')->nullable();
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
        Schema::dropIfExists('online_order');
    }
}

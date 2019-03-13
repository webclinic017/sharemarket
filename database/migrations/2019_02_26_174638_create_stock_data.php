<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_data', function (Blueprint $table) {
            $table->increments('id');
            $table->string('symbol');
            $table->char('series', 3);
            $table->date('date');
            $table->float('prev_close');
            $table->float('open');
            $table->float('high');
            $table->float('low');
            $table->float('close');
            $table->float('last_price');
            $table->float('vwap');
            $table->unsignedDecimal('total_traded_qty', 20, 2);
            $table->unsignedDecimal('turnover', 20, 2);
            $table->unsignedDecimal('no_of_trades', 20, 2);
            $table->unsignedDecimal('deliverable_qty', 20, 2);
            $table->unsignedDecimal('per_delqty_to_trdqty', 20, 2);
            $table->unsignedInteger('combine_oi')->nullable();
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
        Schema::dropIfExists('stock_data');
    }
}

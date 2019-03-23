<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParticipantOI extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participant_oi', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->char('client_type', 8);
            $table->bigInteger('future_index_long');
            $table->bigInteger('future_index_short');
            $table->bigInteger('option_index_call_long');
            $table->bigInteger('option_index_put_long');
            $table->bigInteger('option_index_call_short');
            $table->bigInteger('option_index_put_short');
            $table->bigInteger('future_stock_long');
            $table->bigInteger('future_stock_short');
            $table->bigInteger('option_stock_call_long');
            $table->bigInteger('option_stock_put_long');
            $table->bigInteger('option_stock_call_short');
            $table->bigInteger('option_stock_put_short');
            $table->smallInteger('index_long_per');
            $table->smallInteger('index_short_per');
            $table->decimal('stock_long_per', 5, 2);
            $table->decimal('stock_short_per', 5, 2);
            $table->timestamps();
            //CLIENT_TYPE	Future Index Long	Future Index Short	Future Stock Long	Future Stock Short	Option Index Call Long	Option Index Put Long	Option Index Call Short	Option Index Put Short	Option Stock Call Long	Option Stock Put Long	Option Stock Call Short	Option Stock Put Short	Total Long Contracts	Total Short Contracts
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('participant__o_i');
    }
}

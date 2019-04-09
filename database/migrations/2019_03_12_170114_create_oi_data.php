<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOiData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oi_data', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->string('symbol');
            $table->bigInteger('mwpl')->nullable();
            $table->bigInteger('open_interest')->nullable();
            $table->bigInteger('limitNextDay')->nullable();
            $table->smallInteger('watchlist')->nullable();
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
        Schema::dropIfExists('oi_data');
    }
}

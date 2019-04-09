<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOptionChain extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('option_chain_expiry', function (Blueprint $table) {
            $table->increments('id');
            $table->string('symbol');
            $table->date('expirydate');
            $table->string('expiry_type');
        });

        Schema::create('option_chain', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->unsignedBigInteger('oce_id');
            $table->bigInteger('calloi')->nullable();
            $table->bigInteger('callchnginoi')->nullable();
            $table->bigInteger('callvolume')->nullable();
            $table->smallInteger('calliv')->nullable();
            $table->decimal('callltp', 20, 2)->nullable();
            $table->decimal('callnetchng', 20, 2)->nullable();
            $table->bigInteger('callbidqty')->nullable();
            $table->decimal('callbidprice', 20, 2)->nullable();
            $table->decimal('callaskprice', 20, 2)->nullable();
            $table->bigInteger('callaskqty')->nullable();
            $table->decimal('strikeprice', 20, 2)->nullable();
            $table->bigInteger('putoi')->nullable();
            $table->bigInteger('putchnginoi')->nullable();
            $table->bigInteger('putvolume')->nullable();
            $table->smallInteger('putiv')->nullable();
            $table->decimal('putltp', 20, 2)->nullable();
            $table->decimal('putnetchng', 20, 2)->nullable();
            $table->bigInteger('putbidqty')->nullable();
            $table->decimal('putbidprice', 20, 2)->nullable();
            $table->decimal('putaskprice', 20, 2)->nullable();
            $table->bigInteger('putaskqty')->nullable();
            $table->bigInteger('totalcalloi')->nullable();
            $table->bigInteger('totalcallvolume')->nullable();
            $table->bigInteger('totalputoi')->nullable();
            $table->bigInteger('totalputvolume')->nullable();
            $table->decimal('pcr', 3, 2)->nullable();
            $table->decimal('ivratio', 3, 2);
            $table->smallInteger('expiry')->nullable();
            $table->smallInteger('watchlist')->nullable();
        });

        Schema::table('option_chain', function (Blueprint $table) {
            $table->foreign('oce_id')->references('id')->on('option_chain_expiry');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('option_chain');
        Schema::dropIfExists('option_chain_expiry');
    }
}

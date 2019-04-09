<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOISpurt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oi_spurt', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->string('symbol');
            $table->string('instrument')->nullable();
            $table->date('expiry')->nullable();
            $table->bigInteger('strike')->nullable();
            $table->string('optionType')->nullable();
            $table->decimal('ltp', 20, 2)->nullable();
            $table->decimal('prevClose', 20, 2)->nullable();
            $table->decimal('percLtpChange', 20, 2)->nullable();
            $table->bigInteger('latestOI')->nullable();
            $table->bigInteger('previousOI')->nullable();
            $table->bigInteger('oiChange')->nullable();
            $table->bigInteger('volume')->nullable();
            $table->bigInteger('valueInCrores')->nullable();
            $table->decimal('premValueInCrores', 20, 2)->nullable();
            $table->decimal('underlyValue', 20, 2)->nullable();
            $table->bigInteger('type')->nullable();
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
        Schema::dropIfExists('oi_spurt');
    }
}

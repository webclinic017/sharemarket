<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePcr extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pcr', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->unsignedBigInteger('oce_id');
            $table->bigInteger('totalcalloi')->nullable();
            $table->bigInteger('totalcallvolume')->nullable();
            $table->bigInteger('totalputoi')->nullable();
            $table->bigInteger('totalputvolume')->nullable();
            $table->decimal('pcr', 3, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pcr');
    }
}

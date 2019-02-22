<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShareDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('share_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->string('company_name');
            $table->string('symbol');
            $table->string('isin');
            $table->string('sector_index')->nullable();
            $table->float('stock_pe')->nullable();
            $table->float('index_pe')->nullable();
            $table->char('fno', 3)->default('n');
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
        Schema::dropIfExists('share_detail');
    }
}

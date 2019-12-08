<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class IndexRatio extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('index_ratios', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->decimal('pe', 5, 2);
            $table->decimal('pb', 5, 2);
            $table->decimal('divyield', 5, 2);
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
        Schema::dropIfExists('index_ratios');
    }
}

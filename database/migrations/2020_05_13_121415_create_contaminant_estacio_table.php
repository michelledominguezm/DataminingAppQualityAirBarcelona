<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContaminantEstacioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contaminant_estacio', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->bigInteger('contaminant_id')->unsigned();
            $table->bigInteger('estacio_id')->unsigned();

            $table->foreign('contaminant_id')->references('id')->on('contaminants');
            $table->foreign('estacio_id')->references('id')->on('estacions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contaminant_estacio');
    }
}

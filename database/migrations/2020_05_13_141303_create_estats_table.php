<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estats', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id')->primary(); //data_tram
            $table->timestamps();
            $table->bigInteger('tram_id')->unsigned();
            $table->foreign('tram_id')->references('id')->on('trams');
            $table->string('congestio_id')->nullable();
            $table->foreign('congestio_id')->references('id')->on('congestions');
            $table->string('data');
            $table->integer('any');
            $table->integer('mes');
            $table->integer('dia');
            $table->integer('hora');
            $table->integer('minuts');
            $table->integer('estat_actual');
            $table->integer('estat_previst');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('estats');
    }
}

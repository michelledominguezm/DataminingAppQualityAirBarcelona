<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndicadorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('indicadors', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id')->primary();//nom_contaminant
            $table->timestamps();
            $table->string('nom');
            $table->bigInteger('contaminant_id')->unsigned();
            $table->foreign('contaminant_id')->references('id')->on('contaminants');
            $table->string('bo'); //"limInf-limSup"
            $table->string('moderat'); 
            $table->string('regular'); 
            $table->string('dolent');
            $table->string('molt_dolent');
            $table->string('llindar');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('indicadors');
    }
}

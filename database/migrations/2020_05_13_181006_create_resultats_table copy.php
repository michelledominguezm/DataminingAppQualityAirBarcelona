<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResultatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resultats', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->timestamps();
            $table->string('id')->primary(); 
            $table->bigInteger('estacio_id')->unsigned();
            $table->foreign('estacio_id')->references('id')->on('estacions');
            $table->bigInteger('contaminant_id')->unsigned();
            $table->foreign('contaminant_id')->references('id')->on('contaminants');
            $table->string('indicador_id');
            $table->foreign('indicador_id')->references('id')->on('indicadors');
            $table->string('mostra_id');
            $table->foreign('mostra_id')->references('id')->on('mostres');
            $table->string('immissio_id');
            $table->foreign('immissio_id')->references('id')->on('immissions');
            $table->integer('any');
            $table->integer('mes');
            $table->integer('dia');
            $table->float('H01',6,2)->nullable();
            $table->string('R01')->nullable();
            $table->float('H02',6,2)->nullable();
            $table->string('R02')->nullable();
            $table->float('H03',6,2)->nullable();
            $table->string('R03')->nullable();
            $table->float('H04',6,2)->nullable();
            $table->string('R04')->nullable();
            $table->float('H05',6,2)->nullable();
            $table->string('R05')->nullable();
            $table->float('H06',6,2)->nullable();
            $table->string('R06')->nullable();
            $table->float('H07',6,2)->nullable();
            $table->string('R07')->nullable();
            $table->float('H08',6,2)->nullable();
            $table->string('R08')->nullable();
            $table->float('H09',6,2)->nullable();
            $table->string('R09')->nullable();
            $table->float('H10',6,2)->nullable();
            $table->string('R10')->nullable();
            $table->float('H11',6,2)->nullable();
            $table->string('R11')->nullable();
            $table->float('H12',6,2)->nullable();
            $table->string('R12')->nullable();
            $table->float('H13',6,2)->nullable();
            $table->string('R13')->nullable();
            $table->float('H14',6,2)->nullable();
            $table->string('R14')->nullable();
            $table->float('H15',6,2)->nullable();
            $table->string('R15')->nullable();
            $table->float('H16',6,2)->nullable();
            $table->string('R16')->nullable();
            $table->float('H17',6,2)->nullable();
            $table->string('R17')->nullable();
            $table->float('H18',6,2)->nullable();
            $table->string('R18')->nullable();
            $table->float('H19',6,2)->nullable();
            $table->string('R19')->nullable();
            $table->float('H20',6,2)->nullable();
            $table->string('R20')->nullable();
            $table->float('H21',6,2)->nullable();
            $table->string('R21')->nullable();
            $table->float('H22',6,2)->nullable();
            $table->string('R22')->nullable();
            $table->float('H23',6,2)->nullable();
            $table->string('R23')->nullable();
            $table->float('H24',6,2)->nullable();
            $table->string('R24')->nullable();
            $table->string('maxim')->nullable();
            $table->string('minim')->nullable();
            $table->float('mitjana',6,2)->nullable();
            $table->string('qualificacio')->nullable();
            $table->boolean('complet')->default(false);
            $table->boolean('valid')->default(false);
            
        });
    }

    /**
     * ReRerse the migrations.
     *
     * @return Roid
     */
    public function down()
    {
        Schema::dropIfExists('resultats');
    }
}

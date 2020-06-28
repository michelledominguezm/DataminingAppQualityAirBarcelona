<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('immissions', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            //$table->id();
            $table->timestamps();
            $table->string('id')->primary(); //codi_mesurament
            //$table->integer('codi_eoi'); #number
            //$table->integer('codi_provincia'); #number (08- Barcelona / 17 – Girona / 25 -Lleida / 43 -Tarragona)
            //$table->integer('codi_municipi'); #number
            $table->bigInteger('estacio_id')->unsigned();
            $table->foreign('estacio_id')->references('id')->on('estacions');
            //$table->string('nom_estacio');
            //$table->string('municipi');
            //$table->float('latitud',10,8);#number
            //$table->float('longitud',11,8);#number
            //$table->integer('altitud');#number
            //$table->string('tipus_estacio');
            //$table->string('area_urbana');
            $table->bigInteger('contaminant_id')->unsigned();
            $table->foreign('contaminant_id')->references('id')->on('contaminants');
            //$table->string('contaminant');
            //$table->string('unitats');
            //$table->string('punt_mostr');
            $table->string('data');
            $table->integer('any');
            $table->integer('mes');
            $table->integer('dia');
            $table->float('H01',6,2)->nullable();
            $table->string('V01');
            $table->float('H02',6,2)->nullable();
            $table->string('V02');
            $table->float('H03',6,2)->nullable();
            $table->string('V03');
            $table->float('H04',6,2)->nullable();
            $table->string('V04');
            $table->float('H05',6,2)->nullable();
            $table->string('V05');
            $table->float('H06',6,2)->nullable();
            $table->string('V06');
            $table->float('H07',6,2)->nullable();
            $table->string('V07');
            $table->float('H08',6,2)->nullable();
            $table->string('V08');
            $table->float('H09',6,2)->nullable();
            $table->string('V09');
            $table->float('H10',6,2)->nullable();
            $table->string('V10');
            $table->float('H11',6,2)->nullable();
            $table->string('V11');
            $table->float('H12',6,2)->nullable();
            $table->string('V12');
            $table->float('H13',6,2)->nullable();
            $table->string('V13');
            $table->float('H14',6,2)->nullable();
            $table->string('V14');
            $table->float('H15',6,2)->nullable();
            $table->string('V15');
            $table->float('H16',6,2)->nullable();
            $table->string('V16');
            $table->float('H17',6,2)->nullable();
            $table->string('V17');
            $table->float('H18',6,2)->nullable();
            $table->string('V18');
            $table->float('H19',6,2)->nullable();
            $table->string('V19');
            $table->float('H20',6,2)->nullable();
            $table->string('V20');
            $table->float('H21',6,2)->nullable();
            $table->string('V21');
            $table->float('H22',6,2)->nullable();
            $table->string('V22');
            $table->float('H23',6,2)->nullable();
            $table->string('V23');
            $table->float('H24',6,2)->nullable();
            $table->string('V24');
            //$table->string('Georeferencia');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('immissions');
    }
}

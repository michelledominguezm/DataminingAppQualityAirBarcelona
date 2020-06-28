<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estacions', function (Blueprint $table) {
            //$table->id();
            $table->engine = 'InnoDB';
            $table->timestamps();
            $table->bigInteger('id')->unsigned()->primary(); //estacio
            $table->string('nom_estacio');
            $table->string('codi_estacio');
            //$table->integer('codi_zona');
            $table->integer('codi_eoi');
            $table->float('latitud',10,8);
            $table->float('longitud',11,8);
            $table->string('ubicacio');
            $table->integer('codi_districte');
            $table->string('nom_districte');
            $table->integer('codi_barri');
            $table->string('nom_barri');
            $table->string('tipus_estacio_1');
            $table->string('tipus_estacio_2');
            //$table->foreign('contaminant_id')->references('id')->on('contaminants');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('estacions');
    }
}

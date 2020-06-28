<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCongestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('congestions', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->timestamps();
            $table->string('id')->primary(); 
            $table->bigInteger('tram_id')->unsigned();
            $table->foreign('tram_id')->references('id')->on('trams');
            $table->integer('data');
            $table->integer('any');
            $table->integer('mes');
            $table->integer('dia');
            $table->string('H01')->nullable();
            $table->string('P01')->nullable();
            $table->string('H02')->nullable();
            $table->string('P02')->nullable();
            $table->string('H03')->nullable();
            $table->string('P03')->nullable();
            $table->string('H04')->nullable();
            $table->string('P04')->nullable();
            $table->string('H05')->nullable();
            $table->string('P05')->nullable();
            $table->string('H06')->nullable();
            $table->string('P06')->nullable();
            $table->string('H07')->nullable();
            $table->string('P07')->nullable();
            $table->string('H08')->nullable();
            $table->string('P08')->nullable();
            $table->string('H09')->nullable();
            $table->string('P09')->nullable();
            $table->string('H10')->nullable();
            $table->string('P10')->nullable();
            $table->string('H11')->nullable();
            $table->string('P11')->nullable();
            $table->string('H12')->nullable();
            $table->string('P12')->nullable();
            $table->string('H13')->nullable();
            $table->string('P13')->nullable();
            $table->string('H14')->nullable();
            $table->string('P14')->nullable();
            $table->string('H15')->nullable();
            $table->string('P15')->nullable();
            $table->string('H16')->nullable();
            $table->string('P16')->nullable();
            $table->string('H17')->nullable();
            $table->string('P17')->nullable();
            $table->string('H18')->nullable();
            $table->string('P18')->nullable();
            $table->string('H19')->nullable();
            $table->string('P19')->nullable();
            $table->string('H20')->nullable();
            $table->string('P20')->nullable();
            $table->string('H21')->nullable();
            $table->string('P21')->nullable();
            $table->string('H22')->nullable();
            $table->string('P22')->nullable();
            $table->string('H23')->nullable();
            $table->string('P23')->nullable();
            $table->string('H24')->nullable();
            $table->string('P24')->nullable();
            $table->string('actual')->nullable();
            $table->string('previst')->nullable();
            $table->boolean('complet')->default(false);
            
        });
    }

    /**
     * ReRerse the migrations.
     *
     * @return Roid
     */
    public function down()
    {
        Schema::dropIfExists('congestions');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTramsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trams', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            //$table->id();
            $table->timestamps();
            $table->bigInteger('id')->unsigned()->primary(); //Tram
            $table->string('descripcio');
            $table->text('coordenades');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trams');
    }
}

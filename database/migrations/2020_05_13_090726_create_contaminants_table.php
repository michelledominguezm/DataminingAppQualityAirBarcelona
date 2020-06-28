<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContaminantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contaminants', function (Blueprint $table) {
            //$table->id();
            $table->engine = 'InnoDB';
            $table->timestamps();
            $table->bigInteger('id')->unsigned()->primary(); 
            $table->string('nom')->nullable();
            $table->string('simbol');
            $table->string('unitats');
            $table->string('descripcio')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contaminants');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLevantamientoArrestoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('levantamiento_arresto', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_dem');
            $table->string('nombre')->comment('nombre de la persona que levantara el demerito');
            $table->string('ndoc');
            $table->string('tipodoc');
            $table->date('fecha');
            $table->string('descripcion',600);
            $table->integer('estado');
            $table->timestamps();

            $table->foreign('id_dem')->references('id')->on('personal_faltas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('levantamiento_arresto');
    }
}

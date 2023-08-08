<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDosspatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dosspat', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patiente_id')->nullable();
            $table->integer('numero');
            $table->date('date_creation');
            $table->date('date_accouchement_prévue');
            $table->string('symptomes_actuel');
            $table->string('allergies');
            $table->string('nom_medecin');
            $table->timestamps();
            $table->foreign('patiente_id')->references('id')->on('pat')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dosspat');
    }
}

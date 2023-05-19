<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alumnus_scientific_degree', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('alumnus_id');
            $table->unsignedBigInteger('scientific_degree_id');
            $table->timestamps();

            $table->unique(['alumnus_id', 'scientific_degree_id']);
            $table->foreign('alumnus_id')->references('id')->on('alumni')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('scientific_degree_id')->references('id')->on('scientific_degrees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alumnus_scientific_degree');
    }
};

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
        Schema::create('alumnus_research_field', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('alumnus_id');
            $table->unsignedBigInteger('research_field_id');
            $table->timestamps();

            $table->unique(['alumnus_id', 'research_field_id']);
            $table->foreign('alumnus_id')->references('id')->on('alumni')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('research_field_id')->references('id')->on('research_fields')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alumnus_research_field');
    }
};

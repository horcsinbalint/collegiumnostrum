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
        Schema::create('alumnus_university_faculty', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('alumnus_id');
            $table->unsignedBigInteger('university_faculty_id');
            $table->timestamps();

            $table->unique(['alumnus_id', 'university_faculty_id'], 'alumnus_university_faculty_unique');
            $table->foreign('alumnus_id', 'alumnus_fk')->references('id')->on('alumni')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('university_faculty_id', 'university_faculty_fk')->references('id')->on('university_faculties')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alumnus_university_faculty');
    }
};

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
        Schema::create('alumnus_course', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('alumnus_id');
            $table->unsignedBigInteger('course_id');
            $table->timestamps();

            $table->unique(['alumnus_id', 'course_id']);
            $table->foreign('alumnus_id')->references('id')->on('alumni')->onDelete('cascade');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alumnus_course');
    }
};

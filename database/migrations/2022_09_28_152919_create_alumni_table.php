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
        Schema::create('alumni', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('date_of_birth')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->string('high_school')->nullable();
            $table->string('university_faculty')->nullable();
            $table->text('further_course_detailed')->nullable();
            $table->year('start_of_membership')->nullable();
            $table->text('recognations')->nullable();
            $table->boolean('agreed')->default(false);

            
            // TODO: Kapcsoló tábla: course, further_course, scientific_degree
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alumni');
    }
};

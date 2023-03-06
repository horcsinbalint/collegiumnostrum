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
            $table->string('email')->nullable();
            $table->year('birth_date')->nullable();
            $table->string('birth_place')->nullable();
            $table->string('high_school')->nullable();
            $table->year('graduation_date')->nullable();
            $table->string('university_faculty')->nullable();
            $table->text('further_course_detailed')->nullable();
            $table->year('start_of_membership')->nullable();
            $table->text('recognations')->nullable();
            $table->text('research_field_detailed')->nullable();
            $table->text('links')->nullable();
            $table->text('works')->nullable();
            $table->boolean('agreed')->default(false);


            // Kapcsoló tábla: major, further_course, scientific_degree, research_field
            // TODO: research_field
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

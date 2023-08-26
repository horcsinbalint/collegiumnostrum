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
        Schema::table('alumnus_scientific_degree', function (Blueprint $table) {
            $table->year('year')->nullable();
        });
        Schema::table('scientific_degrees', function (Blueprint $table) {
            $table->dropColumn('obtain_year');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('alumnus_scientific_degree', function (Blueprint $table) {
            $table->dropColumn('year');
        });
        Schema::table('scientific_degrees', function (Blueprint $table) {
            $table->year('obtain_year')->nullable();
        });
    }
};

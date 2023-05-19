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
        Schema::table('alumni', function (Blueprint $table) {
            $table->unsignedBigInteger('pair_id')->nullable(); //the draft or normal pair of the entry
            $table->boolean('is_draft');

            $table->foreign('pair_id')->references('id')->on('alumni');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('alumni', function (Blueprint $table) {
            $table->dropColumn('pair_id');
            $table->dropColumn('is_draft');
        });
    }
};

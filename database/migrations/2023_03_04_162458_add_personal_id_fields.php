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
        Schema::table('objectives', function (Blueprint $table) {            
            $table->unsignedBigInteger('personal_id');
        });
        Schema::table('experiences', function (Blueprint $table) {            
            $table->unsignedBigInteger('personal_id');
        });
        Schema::table('projects', function (Blueprint $table) {            
            $table->unsignedBigInteger('personal_id');
        });
        Schema::table('skills', function (Blueprint $table) {            
            $table->unsignedBigInteger('personal_id');
        });
        Schema::table('interests', function (Blueprint $table) {            
            $table->unsignedBigInteger('personal_id');
        });
        Schema::table('languages', function (Blueprint $table) {            
            $table->unsignedBigInteger('personal_id');
        });
        Schema::table('educations', function (Blueprint $table) {            
            $table->unsignedBigInteger('personal_id');
        });
        Schema::table('socials', function (Blueprint $table) {            
            $table->unsignedBigInteger('personal_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('objectives', function (Blueprint $table) {
            $table->dropColumn('personal_id');
        });
        Schema::table('experiences', function (Blueprint $table) {
            $table->dropColumn('personal_id');
        });
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('personal_id');
        });
        Schema::table('skills', function (Blueprint $table) {
            $table->dropColumn('personal_id');
        });
        Schema::table('interests', function (Blueprint $table) {
            $table->dropColumn('personal_id');
        });
        Schema::table('languages', function (Blueprint $table) {
            $table->dropColumn('personal_id');
        });
        Schema::table('educations', function (Blueprint $table) {
            $table->dropColumn('personal_id');
        });
        Schema::table('socials', function (Blueprint $table) {
            $table->dropColumn('personal_id');
        });
    }
};

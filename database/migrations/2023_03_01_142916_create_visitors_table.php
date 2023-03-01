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
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->ipAddress('userip')->nullable();
            $table->string('referrer')->nullable();
            $table->string('useragent')->nullable();
            $table->string('datetime')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('countryCode')->nullable();
            $table->string('continent')->nullable();
            $table->string('continentCode')->nullable();
            $table->string('timezone')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
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
        Schema::dropIfExists('visitors');
    }
};

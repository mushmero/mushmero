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
        Schema::create('settings_pdfs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('margin_top')->nullable();
            $table->integer('margin_bottom')->nullable();
            $table->integer('margin_left')->nullable();
            $table->integer('margin_right')->nullable();
            $table->integer('header')->nullable();
            $table->integer('footer')->nullable();
            $table->unsignedBigInteger('user_id');
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
        Schema::dropIfExists('settings_pdfs');
    }
};

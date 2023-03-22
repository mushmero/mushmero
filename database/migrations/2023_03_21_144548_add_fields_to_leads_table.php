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
        Schema::table('leads', function (Blueprint $table) {
            $table->string('subdomain')->nullable();
            $table->boolean('activate')->default(0);
            $table->dateTime('activate_time')->nullable();
            $table->dateTime('deactivate_time')->nullable();
            $table->text('reason')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn('subdomain');
            $table->dropColumn('activate');
            $table->dropColumn('activate_time');
            $table->dropColumn('deactivate_time');
            $table->dropColumn('reason');
        });
    }
};

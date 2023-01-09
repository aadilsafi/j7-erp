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
         Schema::table('backlisted_stakeholders', function (Blueprint $table) {

            $table->bigInteger('country_id')->default('0');
            $table->bigInteger('city_id')->default('0');
             $table->bigInteger('state_id')->default('0');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('backlisted_stakeholders', function (Blueprint $table) {

            $table->dropColumn(['country_id','city_id','state_id']);

        });
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class updateStatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('states', function (Blueprint $table) {
            $table->string('country_code')->nullable();
            $table->string('fips_code')->nullable();
            $table->string('iso2')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->boolean('flag')->default(0);
            $table->text('wikiDataId')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('states', function (Blueprint $table) {
            $table->dropColumn([
                'country_code', 'iso2', 'fips_code', 'latitude', 'longitude', 'flag', 'wikiDataId'
            ]);
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('cities', function (Blueprint $table) {

            $table->string('state_code')->nullable();
            $table->unsignedInteger('country_id')->index()->nullable();
            $table->string('country_code')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->boolean('flag')->default(false);
            $table->string('wikiDataId')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cities', function (Blueprint $table) {
            $table->dropColumn([
                'state_code', 'country_id', 'country_code', 'latitude', 'longitude', 'flag', 'wikiDataId'
            ]);
        });
    }
}

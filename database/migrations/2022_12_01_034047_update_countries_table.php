<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('countries', function (Blueprint $table) {
            $table->dropColumn(['short_label']);

            $table->string('iso3')->nullable();
            $table->string('iso2')->nullable();
            $table->string('phonecode')->nullable();
            $table->string('capital')->nullable();
            $table->string('currency')->nullable();
            $table->string('currency_symbol')->nullable();
            $table->string('tld')->nullable();
            $table->string('native')->nullable();
            $table->string('region')->nullable();
            $table->string('subregion')->nullable();
            $table->text('timezones')->nullable();
            $table->text('translations')->nullable();
            $table->text('latitude')->nullable();
            $table->text('longitude')->nullable();
            $table->text('emoji')->nullable();
            $table->text('emojiU')->nullable();
            $table->boolean('flag')->default(false);
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
        Schema::table('countries', function (Blueprint $table) {
            $table->string('short_label')->nullable();

            $table->dropColumn([
                'iso3', 'iso2', 'phonecode', 'capital', 'currency', 'currency_symbol', 'tld', 'native', 'region', 'subregion',
                'timezones', 'translations', 'latitude', 'longitude', 'emoji', 'emojiU', 'flag', 'wikiDataId'
            ]);
        });
    }
}

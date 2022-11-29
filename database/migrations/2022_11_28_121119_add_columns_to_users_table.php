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
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('phone_no', 'contact');

            $table->foreignId('country_id')->default(1)->nullable();
            $table->foreignId('state_id')->default(0)->nullable();
            $table->foreignId('city_id')->default(0)->nullable();
            $table->string('nationality', 50)->default('pakistani');
            $table->string('designation', 50)->nullable();
            $table->string('cnic')->nullable();
            $table->json('countryDetails')->after('contact')->nullable();
            $table->string('optional_contact')->after('contact')->nullable();
            $table->json('OptionalCountryDetails')->after('optional_contact')->nullable();
            $table->string('address')->nullable();
            $table->string('mailing_address')->after('address')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('contact', 'phone_no');

            $table->dropColumn([
                'country_id', 'state_id', 'city_id', 'nationality', 'designation', 'cnic', 'countryDetails', 'optional_contact', 'OptionalCountryDetails', 'address', 'mailing_address'
            ]);
        });
    }
};

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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('contact');
            $table->string('password');
            $table->foreignId('country_id')->default(167)->nullable();
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
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};

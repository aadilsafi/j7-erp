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
        Schema::create('stakeholders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained();
            $table->string('full_name', 50)->nullable();
            $table->string('father_name', 50)->nullable();
            $table->string('occupation', 50)->nullable();
            $table->string('designation', 50)->nullable();
            $table->string('cnic')->nullable();
            $table->string('ntn')->nullable();
            $table->string('contact', 20)->nullable();
            $table->json('countryDetails')->after('contact')->nullable();
            $table->string('email')->nullable();
            $table->string('optional_email')->nullable();
            $table->string('mailing_address')->after('address')->nullable();
            $table->string('optional_contact')->after('contact')->nullable();
            $table->json('OptionalCountryDetails')->after('optional_contact')->nullable();
            $table->string('stakeholder_as')->nullable()->default('i');
            $table->string('address')->nullable();
            $table->foreignId('country_id')->default(167)->nullable();
            $table->foreignId('state_id')->default(0)->nullable();
            $table->foreignId('city_id')->default(0)->nullable();
            $table->string('nationality', 50)->default('pakistani');
            $table->string('comments')->nullable();
            $table->integer('parent_id')->default(0);
            $table->string('relation')->nullable();
            $table->jsonb('optional_contact_number')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stakeholders');
    }
};

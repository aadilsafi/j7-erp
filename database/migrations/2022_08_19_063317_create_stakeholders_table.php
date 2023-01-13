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
            $table->string('crm_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->string('pin_code')->nullable();
            $table->foreignId('site_id')->constrained();
            $table->string('full_name')->nullable();
            $table->string('father_name')->nullable();
            $table->string('cnic')->nullable();
            $table->string('passport_no')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->boolean('is_filer')->nullable(1);
            $table->string('ntn')->nullable();
            $table->string('strn')->nullable();
            $table->boolean('is_local')->default(1);
            $table->string('designation')->nullable();
            $table->string('occupation')->nullable();
            $table->string('industry')->nullable();
            $table->integer('nationality')->default(167);
            $table->integer('origin')->default(167);
            $table->string('email')->nullable();
            $table->string('office_email')->nullable();
            $table->string('mobile_contact')->nullable();
            $table->json('mobileContactCountryDetails')->nullable();
            $table->string('office_contact')->nullable();
            $table->json('OfficeContactCountryDetails')->nullable();
            // residential_address
            $table->string('residential_address')->nullable();
            $table->string('residential_address_type')->nullable();
            $table->foreignId('residential_country_id')->default(167)->nullable();
            $table->foreignId('residential_state_id')->default(0)->nullable();
            $table->foreignId('residential_city_id')->default(0)->nullable();
            $table->string('residential_postal_code')->nullable();
            // mailing_address
            $table->string('mailing_address')->nullable();
            $table->string('mailing_address_type')->nullable();
            $table->foreignId('mailing_country_id')->default(167)->nullable();
            $table->foreignId('mailing_state_id')->default(0)->nullable();
            $table->foreignId('mailing_city_id')->default(0)->nullable();
            $table->string('mailing_postal_code')->nullable();

            $table->string('stakeholder_as')->nullable()->default('i');
            $table->string('referred_by')->nullable();
            $table->integer('source')->nullable();
            $table->string('comments')->nullable();
            $table->integer('parent_id')->default(0);
            $table->string('parent_company')->nullable();
            $table->string('parent_company_id')->nullable();
            $table->string('website')->nullable();
            $table->string('relation')->nullable();
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

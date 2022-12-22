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
        Schema::create('temp_company_stakeholders', function (Blueprint $table) {
            $table->id();
            $table->string('company_name')->nullable();
            $table->string('registration')->nullable();
            $table->string('industry')->nullable();
            $table->string('strn')->nullable();
            $table->string('ntn')->nullable();
            $table->string('origin')->nullable();
            $table->string('email')->nullable();
            $table->string('office_email')->nullable();
            $table->string('mobile_contact')->nullable();
            $table->string('office_contact')->nullable();
            $table->string('website')->nullable();
            $table->string('parent_company')->nullable();
            // residential_address
            $table->string('residential_address')->nullable();
            $table->string('residential_address_type')->nullable();
            $table->string('residential_country')->nullable();
            $table->string('residential_state')->nullable();
            $table->string('residential_city')->nullable();
            $table->string('residential_postal_code')->nullable();

            $table->boolean('same_address_for_mailing')->default(false);

            // mailing_address
            $table->string('mailing_address')->nullable();
            $table->string('mailing_address_type')->nullable();
            $table->string('mailing_country')->nullable();
            $table->string('mailing_state')->nullable();
            $table->string('mailing_city')->nullable();
            $table->string('mailing_postal_code')->nullable();

            $table->string('comments')->nullable();
            $table->boolean('is_dealer')->default(false);
            $table->boolean('is_vendor')->default(false);
            $table->boolean('is_customer')->default(false);
            $table->boolean('is_kin')->default(false);
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
        Schema::dropIfExists('temp_company_stakeholders');
    }
};

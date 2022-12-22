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
        Schema::create('temp_stakeholders', function (Blueprint $table) {
            $table->id();
            $table->string('full_name')->nullable();
            $table->string('father_name')->nullable();
            $table->string('occupation')->nullable();
            $table->string('designation')->nullable();
            $table->string('cnic')->nullable();
            $table->string('passport_no')->nullable();
            $table->boolean('is_local')->default(true);
            $table->string('nationality')->default('Pakistan');
            $table->date('date_of_birth')->nullable();
            $table->string('email')->nullable();
            $table->string('office_email')->nullable();
            $table->string('mobile_contact')->nullable();
            $table->string('office_contact')->nullable();
            $table->string('ntn')->nullable();
            $table->string('referred_by')->nullable();
            $table->string('source')->nullable();

            $table->boolean('same_address_for_mailing')->default(false);

            // residential_address
            $table->string('residential_address')->nullable();
            $table->string('residential_address_type')->nullable();
            $table->string('residential_country')->nullable();
            $table->string('residential_state')->nullable();
            $table->string('residential_city')->nullable();
            $table->string('residential_postal_code')->nullable();
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
        Schema::dropIfExists('temp_stakeholders');
    }
};

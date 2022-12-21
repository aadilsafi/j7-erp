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
        Schema::create('stakeholder_contacts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('stakeholder_id');
            $table->integer('stakeholder_contact_id')->nullable();
            $table->bigInteger('country_id')->default(167)->nullable();
            $table->bigInteger('state_id')->default(0)->nullable();
            $table->bigInteger('city_id')->default(0)->nullable();
            $table->string('nationality')->default('pakistani');
            $table->string('full_name')->nullable();
            $table->string('father_name')->nullable();
            $table->string('occupation')->nullable();
            $table->string('designation')->nullable();
            $table->string('cnic')->nullable();
            $table->string('ntn')->nullable();
            $table->string('contact')->nullable();
            $table->json('countryDetails')->nullable();
            $table->string('address')->nullable();
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
        Schema::dropIfExists('stakeholder_contacts');
    }
};

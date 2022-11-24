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
        Schema::table('stakeholders', function (Blueprint $table) {
            $table->string('mailing_address')->after('address')->nullable();
            $table->string('optional_contact')->after('contact')->nullable();
            $table->json('OptionalCountryDetails')->after('optional_contact')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stakeholders', function (Blueprint $table) {
            $table->dropColumn('mailing_address');
            $table->dropColumn('optional_contact');
            $table->dropColumn('OptionalCountryDetails');
        });
    }
};

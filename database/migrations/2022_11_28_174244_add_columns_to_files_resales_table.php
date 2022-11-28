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
        Schema::table('file_resales', function (Blueprint $table) {
        
            $table->string('new_resale_rate')->nullable();
            $table->string('premium_demand')->nullable();
            $table->string('marketing_service_charges')->nullable();
            $table->dateTime('created_date')->nullable();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('file_resales', function (Blueprint $table) {
            $table->dropColumn(['new_resale_rate', 'premium_demand', 'marketing_service_charges','created_date']);
        });
    }
};

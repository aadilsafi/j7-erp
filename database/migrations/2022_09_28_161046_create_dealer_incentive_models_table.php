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
        Schema::create('dealer_incentive_models', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained();
            $table->foreignId('dealer_id')->constrained('stakeholders');
            $table->longText('dealer_data')->nullable();
            $table->string('dealer_incentive')->nullable();
            $table->string('total_unit_area')->nullable();
            $table->string('unit_IDs')->nullable();
            $table->string('total_dealer_incentive')->nullable();
            $table->boolean('status')->default(0);
            $table->longText('comments')->nullable();
            $table->string('serial_no')->nullable()->default('DI-');
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
        Schema::dropIfExists('dealer_incentive_models');
    }
};

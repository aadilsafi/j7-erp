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
        Schema::create('rebate_incentive_models', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained();
            $table->foreignId('unit_id')->constrained('units');
            $table->foreignId('stakeholder_id')->constrained('stakeholders');
            $table->longText('stakeholder_data')->nullable();
            $table->longText('unit_data')->nullable();
            $table->string('deal_type')->nullable();
            $table->string('commision_percentage')->nullable();
            $table->string('commision_total')->nullable();
            $table->boolean('status')->default(0);
            $table->longText('comments')->nullable();
            $table->foreignId('dealer_id')->constrained('stakeholders');
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
        Schema::dropIfExists('rebate_incentive_models');
    }
};

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
            $table->bigInteger('site_id')->nullable();
            $table->bigInteger('unit_id')->nullable();
            $table->bigInteger('stakeholder_id')->constrained('stakeholders');
            $table->longText('stakeholder_data')->nullable();
            $table->longText('unit_data')->nullable();
            $table->string('deal_type')->nullable();
            $table->string('commision_percentage')->nullable();
            $table->string('commision_total')->nullable();
            $table->boolean('status')->default(0);
            $table->longText('comments')->nullable();
            $table->bigInteger('dealer_id')->nullable();
            $table->bigInteger('bank_id')->nullable();
            $table->string('mode_of_payment')->nullable();
            $table->string('other_value')->nullable();
            $table->string('cheque_no')->nullable();
            $table->string('online_instrument_no')->nullable();
            $table->string('transaction_date')->nullable();
            $table->string('amount_received')->nullable();
            $table->boolean('is_for_dealer_incentive')->default(true);
            $table->timestamp('created_date')->nullable();
            $table->string('serial_no')->nullable()->default('RI-');
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

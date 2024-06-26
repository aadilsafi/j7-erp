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
            $table->string('doc_no')->nullable();
            $table->bigInteger('site_id')->nullable();
            $table->bigInteger('dealer_id')->nullable();
            $table->longText('dealer_data')->nullable();
            $table->string('dealer_incentive')->nullable();
            $table->string('total_unit_area')->nullable();
            $table->string('unit_IDs')->nullable();
            $table->string('total_dealer_incentive')->nullable();
            $table->boolean('status')->default(0);
            $table->longText('comments')->nullable();
            $table->string('serial_no')->nullable()->default('DI-');
            $table->bigInteger('user_id')->nullable();
            $table->timestamp('checked_date')->nullable();
            $table->bigInteger('checked_by')->nullable();
            $table->bigInteger('approved_by')->nullable();
            $table->timestamp('approved_date')->nullable();
            $table->bigInteger('reverted_by')->nullable();
            $table->timestamp('reverted_date')->nullable();
            $table->bigInteger('cheque_active_by')->nullable();
            $table->timestamp('cheque_active_date')->nullable();
            $table->softDeletes();
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

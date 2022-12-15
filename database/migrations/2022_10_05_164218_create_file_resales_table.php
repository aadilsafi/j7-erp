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
        Schema::create('file_resales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained();
            $table->foreignId('file_id')->constrained('file_management');
            $table->foreignId('unit_id')->constrained('units');
            $table->foreignId('sales_plan_id')->constrained();
            $table->foreignId('stakeholder_id')->constrained('stakeholders');
            $table->longText('stakeholder_data')->nullable();
            $table->longText('unit_data')->nullable();
            $table->string('amount_to_be_refunded')->nullable();
            $table->string('amount_profit')->nullable();
            $table->string('payment_due_date')->nullable();
            $table->string('amount_remarks')->nullable();
            $table->string('new_resale_rate')->nullable();
            $table->string('premium_demand')->nullable();
            $table->string('marketing_service_charges')->nullable();
            $table->dateTime('created_date')->nullable();
            // $table->string('rebate_amount')->nullable();
            $table->boolean('status')->default(0);
            $table->longText('comments')->nullable();
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
        Schema::dropIfExists('file_resales');
    }
};

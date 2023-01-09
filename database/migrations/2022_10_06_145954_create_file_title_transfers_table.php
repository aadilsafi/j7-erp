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
        Schema::create('file_title_transfers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('site_id')->nullable();
            $table->bigInteger('file_id')->nullable();
            $table->bigInteger('unit_id')->nullable();
            $table->bigInteger('sales_plan_id')->nullable();
            $table->bigInteger('stakeholder_id')->nullable();
            $table->longText('stakeholder_data')->nullable();
            $table->bigInteger('transfer_person_id')->nullable();
            $table->longText('transfer_person_data')->nullable();
            $table->json('kin_data')->nullable();
            $table->longText('unit_data')->nullable();
            $table->string('transfer_rate')->nullable();
            $table->string('amount_to_be_paid')->nullable();
            $table->string('payment_due_date')->nullable();
            $table->string('amount_remarks')->nullable();
            $table->boolean('paid_status')->default(0);
            $table->timestamp('payment_date')->nullable();
            $table->boolean('status')->default(0);
            $table->longText('comments')->nullable();
            $table->string('serial_no')->nullable()->default('FTT-');
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
        Schema::dropIfExists('file_title_transfers');
    }
};

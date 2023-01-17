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
        Schema::create('payment_vocuhers', function (Blueprint $table) {
            $table->id();
            $table->string('doc_no')->nullable();
            $table->bigInteger('site_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->bigInteger('customer_id')->nullable();
            $table->bigInteger('dealer_id')->nullable();
            $table->bigInteger('vendor_id')->nullable();
            $table->bigInteger('bank_id')->nullable();
            $table->string('stakeholder_type')->nullable();
            $table->string('customer_ap_account')->nullable();
            $table->string('dealer_ap_account')->nullable();
            $table->string('vendor_ap_account')->nullable();
            $table->string('customer_dealer_vendor_details')->nullable();
            $table->string('name')->nullable();
            $table->string('representative')->nullable();
            $table->string('business_type')->nullable();
            $table->string('identity_number')->nullable();
            $table->string('ntn')->nullable();
            $table->string('tax_status')->nullable();
            $table->string('bussiness_address')->nullable();
            $table->string('transaction_details')->nullable();
            $table->string('description')->nullable();
            $table->string('account_payable')->nullable();
            $table->string('expense_account')->nullable();
            $table->string('total_payable_amount')->nullable();
            $table->string('advance_given')->nullable();
            $table->string('discount_recevied')->nullable();
            $table->string('net_payable')->nullable();
            $table->string('payment_mode')->nullable();
            $table->string('transaction_mode')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->timestamp('receiving_date')->nullable();
            $table->timestamp('approved_date')->nullable();
            $table->bigInteger('approved_by')->nullable();
            $table->string('comments')->nullable();
            $table->string('amount_to_be_paid')->nullable();
            $table->boolean('status')->default(0);
            $table->boolean('cheque_status')->default(0);
            $table->string('serial_no')->nullable()->default('PV-');
            $table->timestamp('checked_date')->nullable();
            $table->bigInteger('checked_by')->nullable();
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
        Schema::dropIfExists('payment_vocuhers');
    }
};

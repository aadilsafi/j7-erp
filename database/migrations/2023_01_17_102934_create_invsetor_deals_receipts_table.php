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
        Schema::create('invsetor_deals_receipts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('site_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->bigInteger('investor_deal_id')->nullable();
            $table->bigInteger('investor_id')->nullable();
            $table->bigInteger('bank_id')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('doc_no')->nullable();
            $table->string('total_received_amount')->nullable();
            $table->string('total_payable_amount')->nullable();
            $table->timestamp('created_date')->nullable();
            $table->bigInteger('checked_by')->nullable();
            $table->timestamp('checked_date')->nullable();
            $table->bigInteger('active_by')->nullable();
            $table->timestamp('active_date')->nullable();
            $table->bigInteger('bounced_by')->nullable();
            $table->timestamp('bounced_by_date')->nullable();
            $table->bigInteger('reverted_by')->nullable();
            $table->timestamp('reverted_date')->nullable();
            $table->string('jve_number')->nullable();
            $table->string('status')->nullable();
            $table->text('remarks')->nullable();
            $table->string('name')->nullable();
            $table->string('cnic')->nullable();
            $table->string('phone_no')->nullable();
            $table->string('mode_of_payment')->nullable();
            $table->string('other_value')->nullable();
            $table->string('cheque_no')->nullable();
            $table->string('online_instrument_no')->nullable();
            $table->string('transaction_date')->nullable();
            $table->string('discounted_amount')->nullable();
            $table->string('other_purpose')->nullable();
            $table->string('bank_details')->nullable();
            $table->string('investor_ar_account')->nullable();
            $table->string('customer_ap_amount')->nullable();
            $table->string('customer_ap_account')->nullable();
            $table->string('dealer_ap_amount')->nullable();
            $table->string('dealer_ap_account')->nullable();
            $table->string('vendor_ap_amount')->nullable();
            $table->string('vendor_ap_account')->nullable();
            $table->string('investor_ap_amount')->nullable();
            $table->string('investor_ap_account')->nullable();
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
        Schema::dropIfExists('invsetor_deals_receipts');
    }
};

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
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->string('doc_no')->nullable();
            $table->bigInteger('site_id')->nullable();
            $table->string('serial_no')->nullable()->default('REC-');
            $table->bigInteger('unit_id')->nullable();
            $table->bigInteger('sales_plan_id')->nullable();
            $table->bigInteger('bank_id')->nullable();
            $table->string('name')->nullable();
            $table->string('cnic')->nullable();
            $table->string('phone_no')->nullable();
            $table->string('mode_of_payment');
            $table->string('other_value')->nullable();
            $table->string('pay_order')->nullable();
            $table->string('cheque_no')->nullable();
            $table->string('online_instrument_no')->nullable();
            $table->string('drawn_on_bank')->nullable();
            $table->string('transaction_date')->nullable();
            $table->string('amount_in_words')->nullable();
            $table->string('discounted_amount')->nullable();
            $table->double('amount_in_numbers')->nullable();
            $table->string('purpose')->nullable();
            $table->string('other_purpose')->nullable();
            $table->text('installment_number')->nullable();
            $table->string('attachment')->nullable();
            $table->string('amount_received')->nullable();
            $table->string('comments')->nullable();
            $table->double('status')->default(0);
            $table->string('bank_details')->nullable();
            $table->dateTime('created_date')->nullable();
            $table->string('customer_ar_account')->nullable();
            $table->string('customer_ap_amount')->nullable();
            $table->string('customer_ap_account')->nullable();
            $table->string('dealer_ap_amount')->nullable();
            $table->string('dealer_ap_account')->nullable();
            $table->string('vendor_ap_amount')->nullable();
            $table->string('vendor_ap_account')->nullable();
            $table->string('investor_ap_amount')->nullable();
            $table->string('investor_ap_account')->nullable();
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
        Schema::dropIfExists('receipts');
    }
};

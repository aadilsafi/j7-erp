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
        Schema::create('transfer_receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained();
            $table->string('serial_no')->nullable()->default('FT-REC-');
            $table->foreignId('unit_id')->constrained();
            $table->foreignId('file_id');
            $table->foreignId('file_title_transfer_id')->constrained();
            $table->foreignId('stakeholder_id')->constrained();
            $table->string('mode_of_payment');
            $table->string('other_value')->nullable();
            $table->string('pay_order')->nullable();
            $table->string('cheque_no')->nullable();
            $table->string('online_transaction_no')->nullable();
            $table->string('drawn_on_bank')->nullable();
            $table->foreignId('bank_id')->nullable();
            $table->string('transaction_date')->nullable();
            $table->string('discounted_amount')->nullable();
            $table->double('amount_in_numbers');
            $table->string('attachment')->nullable();
            $table->string('amount')->nullable();
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
        Schema::dropIfExists('transfer_receipts');
    }
};

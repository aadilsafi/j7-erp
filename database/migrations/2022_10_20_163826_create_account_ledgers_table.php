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
        Schema::create('account_ledgers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('site_id')->nullable();
            $table->string('account_head_code');
            $table->string('origin_name')->nullable();
            $table->string('origin_number')->nullable();
            $table->bigInteger('account_action_id')->nullable();
            $table->bigInteger('bank_id')->nullable();
            $table->bigInteger('sales_plan_id')->nullable();
            $table->bigInteger('receipt_id')->nullable();
            $table->bigInteger('file_refund_id')->nullable();
            $table->bigInteger('file_resale_id')->nullable();
            $table->bigInteger('file_buyback_id')->nullable();
            $table->bigInteger('file_cancellation_id')->nullable();
            $table->bigInteger('file_title_transfer_id')->nullable();
            $table->bigInteger('rebate_incentive_id')->nullable();
            $table->bigInteger('dealer_incentive_id')->nullable();
            $table->bigInteger('payment_voucher_id')->nullable();
            $table->bigInteger('transfer_receipt_id')->nullable();
            $table->bigInteger('journal_voucher_id')->nullable();
            $table->bigInteger('investor_deal_id')->nullable();
            $table->bigInteger('investor_deal_receipt_id')->nullable();
            $table->double('credit')->default(0);
            $table->double('debit')->default(0);
            $table->double('balance')->default(0);
            $table->string('nature_of_account');
            $table->boolean('status')->default(true);
            $table->boolean('manual_entry')->default(false);
            $table->timestamp('created_date')->nullable();
            $table->foreign('account_head_code')->references('code')->on('account_heads')->cascadeOnDelete()->cascadeOnUpdate();
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
        Schema::dropIfExists('account_ledgers');
    }
};

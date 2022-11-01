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
            $table->foreignId('site_id')->constrained();
            $table->foreignId('unit_id')->constrained();
            $table->foreignId('sales_plan_id')->constrained();
            $table->foreignId('bank_id')->nullable();
            $table->string('name');
            $table->string('cnic');
            $table->string('phone_no');
            $table->string('mode_of_payment');
            $table->string('other_value')->nullable();
            $table->string('pay_order')->nullable();
            $table->string('cheque_no')->nullable();
            $table->string('online_instrument_no')->nullable();
            $table->string('drawn_on_bank')->nullable();
            $table->string('transaction_date')->nullable();
            $table->string('amount_in_words');
            $table->double('amount_in_numbers');
            $table->string('purpose');
            $table->string('other_purpose')->nullable();
            $table->text('installment_number')->nullable();
            $table->string('attachment')->nullable();
            $table->string('amount_received')->nullable();
            $table->string('comments')->nullable();
            $table->double('status')->default(0);
            $table->string('bank_details')->nullable();
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

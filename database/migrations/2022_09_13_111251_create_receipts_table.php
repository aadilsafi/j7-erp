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
            $table->string('name');
            $table->string('cnic');
            $table->string('phone_no');
            $table->string('mode_of_payment');
            $table->string('pay_order')->nullable();
            $table->string('cheque_no')->nullable();
            $table->string('online_instrument_no')->nullable();
            $table->string('drawn_on_bank')->nullable();
            $table->string('transaction_date')->nullable();
            $table->string('amount_in_words');
            $table->double('amount_in_numbers');
            $table->string('purpose');
            $table->double('installment_number')->nullable();
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

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
        Schema::create('temp_receipts', function (Blueprint $table) {
            $table->id();
            $table->string('doc_no')->nullable();
            $table->string('unit_short_label')->nullable();
            $table->bigInteger('stakeholder_cnic')->nullable();
            $table->float('total_price')->nullable();
            $table->float('down_payment_total')->nullable();
            $table->date('validity')->nullable();
            $table->string('mode_of_payment')->nullable();
            $table->string('cheque_no')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_acount_number')->nullable();
            $table->string('online_transaction_no')->nullable();
            $table->date('transaction_date')->nullable();
            $table->string('created_date')->nullable();
            $table->string('other_payment_mode_value')->nullable();
            $table->float('amount')->nullable();
            $table->string('installment_no')->nullable();
            $table->string('status')->nullable();
            $table->string('image_url')->nullable()->nullable();
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
        Schema::dropIfExists('temp_receipts');
    }
};

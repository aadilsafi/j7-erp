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
            $table->string('unit_short_label');
            $table->bigInteger('stakeholder_cnic');
            $table->float('total_price');
            $table->float('down_payment_total');
            $table->date('validity');
            $table->string('mode_of_payment');
            $table->string('cheque_no')->nullable(true);
            $table->string('bank_name')->nullable(true);
            $table->string('online_transaction_no')->nullable(true);
            $table->date('transaction_date')->nullable(true);
            $table->string('other_payment_mode_value')->nullable(true);
            $table->float('amount');
            $table->string('status');
            $table->string('image_url')->nullable(true);
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

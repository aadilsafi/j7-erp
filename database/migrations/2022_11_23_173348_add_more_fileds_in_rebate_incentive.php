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
        Schema::table('rebate_incentive_models', function (Blueprint $table) {
            $table->foreignId('bank_id')->nullable();
            $table->string('mode_of_payment');
            $table->string('other_value')->nullable();
            $table->string('cheque_no')->nullable();
            $table->string('online_instrument_no')->nullable();
            $table->string('transaction_date')->nullable();
            $table->string('amount_received')->nullable();
            $table->timestamp('created_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rebate_incentive', function (Blueprint $table) {
            //
        });
    }
};

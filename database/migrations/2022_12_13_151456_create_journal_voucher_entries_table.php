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
        Schema::create('journal_voucher_entries', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('site_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->bigInteger('account_head_code')->nullable();
            $table->bigInteger('journal_voucher_id')->nullable();
            $table->string('account_number')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('credit')->nullable();
            $table->string('debit')->nullable();
            $table->string('total_debit')->nullable();
            $table->string('total_credit')->nullable();
            $table->string('balance')->nullable();
            $table->string('tax_amount')->nullable();
            $table->string('total_amount')->nullable();
            $table->longText('remarks')->nullable();
            $table->string('status')->default('pending');
            $table->longText('comments')->nullable();
            $table->timestamp('created_date')->nullable();
            $table->bigInteger('checked_by')->nullable();
            $table->timestamp('checked_date')->nullable();
            $table->bigInteger('approved_by')->nullable();
            $table->timestamp('approved_date')->nullable();
            $table->bigInteger('reverted_by')->nullable();
            $table->timestamp('reverted_date')->nullable();
            $table->string('jve_number')->nullable();
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
        Schema::dropIfExists('journal_voucher_entries');
    }
};

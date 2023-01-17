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
        Schema::create('journal_vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('doc_no')->nullable();
            $table->bigInteger('site_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->bigInteger('account_head_code')->nullable();
            $table->string('account_number')->nullable();
            $table->string('name')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('voucher_date')->nullable();
            $table->string('voucher_type')->nullable();
            $table->string('voucher_amount')->nullable();
            $table->string('total_debit')->nullable();
            $table->string('total_credit')->nullable();
            $table->string('status')->default('pending');
            $table->longText('remarks')->nullable();
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
        Schema::dropIfExists('vouchers');
    }
};

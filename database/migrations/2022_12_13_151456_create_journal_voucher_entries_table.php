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
            $table->foreignId('site_id')->constrained();
            $table->foreignId('user_id');
            $table->foreignId('account_head_code')->nullable();
            $table->foreignId('journal_voucher_id')->constrained();
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
            $table->foreignId('checked_by')->nullable();
            $table->timestamp('checked_date')->nullable();
            $table->foreignId('approved_by')->nullable();
            $table->timestamp('approved_date')->nullable();
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

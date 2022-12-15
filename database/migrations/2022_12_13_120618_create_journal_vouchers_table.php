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
            $table->foreignId('site_id')->constrained();
            $table->foreignId('user_id');
            $table->foreignId('account_head_id')->nullable();
            $table->string('account_number')->nullable();
            $table->string('name')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('voucher_date')->nullable();
            $table->string('voucher_type')->nullable();
            $table->string('voucher_amount')->nullable();
            $table->boolean('status')->default(0);
            $table->longText('remarks')->nullable();
            $table->longText('comments')->nullable();
            $table->timestamp('created_date')->nullable();
            $table->foreignId('checked_by')->nullable();
            $table->timestamp('checked_date')->nullable();
            $table->foreignId('approved_by')->nullable();
            $table->timestamp('approved_date')->nullable();
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

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
        Schema::create('temp_journal_voucher_entries', function (Blueprint $table) {
            $table->id();
            $table->string('doc_no')->nullable();
            $table->string('jv_remarks')->nullable();
            $table->string('created_date')->nullable();
            $table->string('user_email')->nullable();
            $table->string('status')->nullable();
            $table->string('checked_by')->nullable();
            $table->string('checked_date')->nullable();
            $table->string('posted_by')->nullable();
            $table->string('posted_date')->nullable();
            $table->string('account_code')->nullable();
            $table->string('credit')->nullable();
            $table->string('debit')->nullable();
            $table->string('remarks')->nullable();
            $table->string('date')->nullable();
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
        Schema::dropIfExists('temp_journal_voucher_entries');
    }
};

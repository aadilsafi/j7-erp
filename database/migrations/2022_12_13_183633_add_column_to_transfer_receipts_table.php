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
        Schema::table('transfer_receipts', function (Blueprint $table) {
            $table->dropColumn('amount_in_numbers');
            $table->string('amount_in_words')->nullable();
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
        Schema::table('transfer_receipts', function (Blueprint $table) {
            $table->double('amount_in_numbers')->nullable();
            $table->dropColumn('amount_in_words');
        });
    }
};

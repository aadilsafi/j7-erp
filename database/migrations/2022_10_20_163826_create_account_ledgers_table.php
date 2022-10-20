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
        Schema::create('account_ledgers', function (Blueprint $table) {
            $table->id();
            $table->string('account_head_code', 20);
            $table->float('credit')->default(0);
            $table->float('debit')->default(0);
            $table->string('nature_of_account', 3);
            $table->boolean('status')->default(true);
            $table->foreign('account_head_code')->references('code')->on('account_heads')->cascadeOnDelete()->cascadeOnUpdate();
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
        Schema::dropIfExists('account_ledgers');
    }
};

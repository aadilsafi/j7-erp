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
        Schema::create('sales_plan_installments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('sales_plan_id')->nullable();
            $table->string('details')->nullable();
            $table->datetime('date')->nullable();
            $table->string('type')->nullable();
            $table->double('amount')->default(0);
            $table->double('paid_amount')->default(0);
            $table->double('remaining_amount')->default(0);
            $table->string('remarks')->nullable();
            $table->string('status')->nullable();
            $table->dateTime('last_paid_at')->nullable();
            $table->tinyInteger('installment_order')->default(0);
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
        Schema::dropIfExists('sales_plan_installments');
    }
};

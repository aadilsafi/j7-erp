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
        Schema::create('temp_sale_plan_installments', function (Blueprint $table) {
            $table->id();
            $table->string('unit_short_label');
            $table->bigInteger('stakeholder_cnic');
            $table->float('total_price');
            $table->float('down_payment_total');
            $table->date('validity');
            $table->string('type');
            $table->string('label')->nullable();
            $table->date('due_date');
            $table->integer('installment_no');
            $table->float('total_amount');
            $table->float('paid_amount');
            $table->float('remaining_amount')->nullable();
            $table->date('last_paid_at')->nullable();
            $table->string('status');
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
        Schema::dropIfExists('temp_sale_plan_installments');
    }
};

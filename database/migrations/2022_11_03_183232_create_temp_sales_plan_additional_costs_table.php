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
        Schema::create('temp_sales_plan_additional_costs', function (Blueprint $table) {
            $table->id();
            $table->string('unit_short_label');
            $table->bigInteger('stakeholder_cnic');
            $table->float('total_price');
            $table->float('down_payment_total');
            $table->date('validity');
            $table->string('additional_costs_name');
            $table->integer('percentage');
            $table->float('total_amount');
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
        Schema::dropIfExists('temp_sales_plan_additional_costs');
    }
};

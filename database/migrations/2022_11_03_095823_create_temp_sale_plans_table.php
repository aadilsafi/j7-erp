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
        Schema::create('temp_sale_plans', function (Blueprint $table) {
            $table->id();
            $table->string('unit_short_label');
            $table->bigInteger('stakeholder_cnic');
            $table->float('unit_price');
            $table->float('total_price');
            $table->float('discount_percentage');
            $table->float('discount_total');
            $table->float('down_payment_percentage');
            $table->float('down_payment_total');
            $table->string('lead_source');
            $table->date('validity');
            $table->string('status');
            $table->string('comment')->nullable();
            $table->string('approved_date')->nullable();
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
        Schema::dropIfExists('temp_sale_plans');
    }
};

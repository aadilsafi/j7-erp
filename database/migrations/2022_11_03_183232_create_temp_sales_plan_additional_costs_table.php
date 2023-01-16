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
            $table->string('sales_plan_doc_no')->nullable();
            $table->string('additional_costs_name')->nullable();
            $table->integer('percentage')->nullable();
            $table->float('total_amount')->nullable();
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

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
        Schema::create('sales_plan_additional_costs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('sales_plan_id')->nullable();
            $table->bigInteger('additional_cost_id')->nullable();
            $table->double('percentage')->default(0);
            $table->double('amount')->default(0);
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
        Schema::dropIfExists('sales_plan_additional_costs');
    }
};

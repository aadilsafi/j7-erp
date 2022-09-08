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
            $table->foreignId('sales_plan_id')->constrained();
            $table->string('details')->nullable();
            $table->string('date')->nullable();
            $table->double('amount')->default(0);
            $table->double('paid_amount')->default(0);
            $table->double('remaining_amount')->default(0);
            $table->string('remarks')->nullable();
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

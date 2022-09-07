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
        Schema::create('sales_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('stakeholder_id')->constrained();
            $table->double('unit_price')->default(0);
            $table->double('total_price')->default(0);
            $table->double('discount_percentage')->default(0);
            $table->double('discount_total')->default(0);
            $table->double('down_payment_percentage')->default(0);
            $table->double('down_payment_total')->default(0);
            $table->string('sales_type')->nullable();
            $table->string('indirect_source')->nullable();
            $table->dateTime('validity')->nullable();
            $table->boolean('status')->default(false);
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
        Schema::dropIfExists('sales_plans');
    }
};

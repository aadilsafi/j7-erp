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
            $table->json('kin_data')->nullable();
            $table->text('stakeholder_data')->nullable();
            $table->double('unit_price')->default(0);
            $table->double('total_price')->default(0);
            $table->double('discount_percentage')->default(0);
            $table->double('discount_total')->default(0);
            $table->double('down_payment_percentage')->default(0);
            $table->double('down_payment_total')->default(0);
            $table->foreignId('lead_source_id')->constrained();
            $table->dateTime('validity')->nullable();
            $table->double('status')->default(0);
            $table->text('comments')->nullable();
            $table->dateTime('approved_date')->nullable();
            $table->dateTime('created_date')->nullable();
            $table->boolean('cancel')->default(0);
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

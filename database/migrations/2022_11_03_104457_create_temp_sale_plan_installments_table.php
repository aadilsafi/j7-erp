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
            $table->string('sales_plan_doc_no')->nullable();
            $table->string('type');
            $table->string('label')->nullable();
            $table->integer('installment_no');
            $table->float('total_amount');
            $table->date('due_date')->nullable();
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

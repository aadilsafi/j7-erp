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
            $table->string('doc_no')->nullable();
            $table->bigInteger('unit_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->bigInteger('stakeholder_id')->nullable();
            $table->json('kin_data')->nullable();
            $table->text('stakeholder_data')->nullable();
            $table->double('unit_price')->default(0);
            $table->double('total_price')->default(0);
            $table->double('discount_percentage')->default(0);
            $table->double('discount_total')->default(0);
            $table->double('down_payment_percentage')->default(0);
            $table->double('down_payment_total')->default(0);
            $table->bigInteger('lead_source_id')->nullable();
            $table->timestamp('validity')->nullable();
            $table->double('status')->default(0);
            $table->text('comments')->nullable();
            $table->timestamp('approved_date')->nullable();
            $table->timestamp('created_date')->nullable();
            $table->boolean('cancel')->default(0);
            $table->string('serial_no')->nullable()->default('SI-');
            $table->boolean('is_from_crm')->default(false);
            $table->bigInteger('checked_by')->nullable();
            $table->timestamp('checked_date')->nullable();
            $table->bigInteger('approved_by')->nullable();
            $table->bigInteger('dis_approved_by')->nullable();
            $table->timestamp('dis_approved_date')->nullable();
            $table->bigInteger('reverted_by')->nullable();
            $table->timestamp('reverted_date')->nullable();
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

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
        Schema::create('stakeholder_investor_deals', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('site_id')->nullable();
            $table->bigInteger('stakeholder_investor_id')->nullable();
            $table->bigInteger('unit_id')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('doc_no')->nullable();
            $table->string('received_amount')->nullable();
            $table->string('payable_amount')->nullable();
            $table->timestamp('created_date')->nullable();
            $table->bigInteger('checked_by')->nullable();
            $table->timestamp('checked_date')->nullable();
            $table->bigInteger('approved_by')->nullable();
            $table->timestamp('approved_date')->nullable();
            $table->bigInteger('reverted_by')->nullable();
            $table->timestamp('reverted_date')->nullable();
            $table->bigInteger('dis_approved_by')->nullable();
            $table->timestamp('dis_approved_date')->nullable();
            $table->string('jve_number')->nullable();
            $table->string('status')->nullable();
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('stakeholder_investor_deals');
    }
};

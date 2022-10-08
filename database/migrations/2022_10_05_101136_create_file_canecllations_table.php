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
        Schema::create('file_canecllations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained();
            $table->foreignId('file_id')->constrained('file_management');
            $table->foreignId('unit_id')->constrained('units');
            $table->foreignId('dealer_id')->nullable();
            $table->foreignId('stakeholder_id')->constrained('stakeholders');
            $table->longText('stakeholder_data')->nullable();
            $table->longText('unit_data')->nullable();
            $table->longText('dealer_data')->nullable();
            $table->string('amount_to_be_refunded')->nullable();
            $table->string('cancellation_charges')->nullable();
            $table->string('payment_due_date')->nullable();
            $table->string('amount_remarks')->nullable();
            $table->boolean('status')->default(0);
            $table->longText('comments')->nullable();
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
        Schema::dropIfExists('file_canecllations');
    }
};

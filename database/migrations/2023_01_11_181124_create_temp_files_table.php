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
        Schema::create('temp_files', function (Blueprint $table) {
            $table->id();
            $table->string('doc_no')->nullable();
            $table->string('unit_short_label')->nullable();
            $table->string('stakeholder_cnic')->nullable();
            $table->string('total_price')->nullable();
            $table->string('down_payment_total')->nullable();
            $table->string('sales_plan_approval_date')->nullable();
            $table->string('registration_no')->nullable();
            $table->string('application_no')->nullable();
            $table->string('note_serial_number')->nullable();
            $table->string('deal_type')->nullable();
            $table->string('created_date')->nullable();
            $table->string('image_url')->nullable();
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
        Schema::dropIfExists('temp_files');
    }
};

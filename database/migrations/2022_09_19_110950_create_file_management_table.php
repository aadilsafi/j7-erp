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
        Schema::create('file_management', function (Blueprint $table) {
            $table->id();
            $table->string('doc_no')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->bigInteger('site_id')->nullable();
            $table->bigInteger('unit_id')->nullable();
            $table->bigInteger('sales_plan_id')->nullable();
            $table->bigInteger('stakeholder_id')->nullable('stakeholders');
            $table->bigInteger('file_action_id')->nullable('file_actions');
            $table->longText('stakeholder_data')->nullable();
            $table->longText('unit_data')->nullable();
            $table->string('registration_no')->nullable();
            $table->string('application_no')->nullable();
            $table->string('deal_type')->nullable();
            $table->string('note_serial_number')->nullable();
            $table->boolean('status')->default(0);
            $table->string('comments')->nullable();
            $table->dateTime('created_date')->nullable();
            $table->string('serial_no')->nullable()->default('UF-');
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
        Schema::dropIfExists('file_management');
    }
};

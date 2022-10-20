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
            $table->foreignId('site_id')->constrained();
            $table->foreignId('unit_id')->constrained('units');
            $table->foreignId('sales_plan_id')->constrained();
            $table->foreignId('stakeholder_id')->constrained('stakeholders');
            $table->foreignId('file_action_id')->constrained('file_actions');
            $table->longText('stakeholder_data')->nullable();
            $table->longText('unit_data')->nullable();
            $table->string('registration_no');
            $table->string('application_no');
            $table->string('deal_type');
            $table->boolean('status')->default(0);
            $table->string('comments')->nullable();
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

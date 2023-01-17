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
        Schema::create('site_configrations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('site_id')->nullable();
            $table->tinyInteger('site_max_floors')->default(0);
            $table->tinyInteger('site_token_percentage')->default(5);
            $table->tinyInteger('site_down_payment_percentage')->default(25);

            $table->string('floor_prefix', 5)->default('F');

            $table->tinyInteger('unit_number_digits')->default(2);

            $table->tinyInteger('salesplan_validity_days')->default(7);
            $table->tinyInteger('salesplan_installment_days')->default(90);
            $table->string('salesplan_master_code')->default('123456');
            $table->bigInteger('salesplan_default_investment_plan_template')->default(1);
            $table->bigInteger('salesplan_default_payment_plan_template')->default(2);

            $table->string('others_bank_name')->nullable();
            $table->string('others_bank_account_name')->nullable();
            $table->string('others_bank_account_no')->nullable();


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
        Schema::dropIfExists('site_configrations');
    }
};

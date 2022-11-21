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
        if (Schema::hasColumn('rebate_incentive_models', 'is_for_dealer_incentive')){
            Schema::table('rebate_incentive_models', function (Blueprint $table) {
                $table->dropColumn('is_for_dealer_incentive');
                $table->boolean('is_for_dealer_incentive')->default(true);
            });
        }else
        {
            Schema::table('rebate_incentive_models', function (Blueprint $table) {
                $table->boolean('is_for_dealer_incentive')->default(true);
            });
        }
       
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('rebate_incentive_models', 'is_for_dealer_incentive')){
            Schema::table('rebate_incentive_models', function (Blueprint $table) {
                $table->dropColumn('is_for_dealer_incentive');
            });
        }
    }
};

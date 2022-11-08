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
        $tables = [
            'stakeholders',
            'floors',
            'types',
            'additional_costs',
            'units',
            'sales_plans',
            'sales_plan_installments',
            'sales_plan_additional_costs',
            'banks',
            'receipts'
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->boolean('is_imported')->default(false);
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
        $tables = [
            'stakeholders',
            'floors',
            'types',
            'additional_costs',
            'units',
            'sales_plans',
            'sales_plan_installments',
            'sales_plan_additional_costs',
            'banks',
            'receipts'
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropColumn('is_imported');
            });
        }
    }
};

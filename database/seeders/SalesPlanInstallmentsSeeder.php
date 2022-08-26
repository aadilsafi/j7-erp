<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use App\Models\SalesPlanInstallments;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SalesPlanInstallmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        // $table->foreignId('sales_plan_id')->constrained();
        //     $table->date('date');
        //     $table->float('amount')->default(0);
        //     $table->string('details')->nullable();
        //     $table->string('remarks')->nullable();
        (new SalesPlanInstallments())->insert([
            [
                'sales_plan_id' => 1,
                'date' => Carbon::parse('2022-12-25'),
                'amount' => 10000,
                'details' => '1',
                'remarks' => 'Paid',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sales_plan_id' => 1,
                'date' => Carbon::parse('2023-03-25'),
                'amount' => 10000,
                'details' => '1',
                'remarks' => 'Paid',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sales_plan_id' => 1,
                'date' => Carbon::parse('2023-06-25'),
                'amount' => 10000,
                'details' => '1',
                'remarks' => 'Paid',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sales_plan_id' => 1,
                'date' => Carbon::parse('2023-09-25'),
                'amount' => 10000,
                'details' => '1',
                'remarks' => 'Paid',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sales_plan_id' => 1,
                'date' => Carbon::parse('2023-12-25'),
                'amount' => 10000,
                'details' => '1',
                'remarks' => 'Paid',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sales_plan_id' => 1,
                'date' => Carbon::parse('2024-03-25'),
                'amount' => 10000,
                'details' => '1',
                'remarks' => 'Not Paid Yet',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sales_plan_id' => 1,
                'date' => Carbon::parse('2024-06-25'),
                'amount' => 10000,
                'details' => '1',
                'remarks' => 'Not Paid Yet',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sales_plan_id' => 1,
                'date' => Carbon::parse('2024-09-25'),
                'amount' => 10000,
                'details' => '1',
                'remarks' => 'Not Paid Yet',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sales_plan_id' => 1,
                'date' => Carbon::parse('2024-12-25'),
                'amount' => 10000,
                'details' => '1',
                'remarks' => 'Not Paid Yet',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sales_plan_id' => 1,
                'date' => Carbon::parse('2025-03-25'),
                'amount' => 10000,
                'details' => '1',
                'remarks' => 'Not Paid Yet',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sales_plan_id' => 1,
                'date' => Carbon::parse('2025-06-25'),
                'amount' => 10000,
                'details' => '1',
                'remarks' => 'Not Paid Yet',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sales_plan_id' => 1,
                'date' => Carbon::parse('2025-09-25'),
                'amount' => 10000,
                'details' => '1',
                'remarks' => 'Not Paid Yet',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sales_plan_id' => 1,
                'date' => Carbon::parse('2025-12-25'),
                'amount' => 10000,
                'details' => '1',
                'remarks' => 'Not Paid Yet',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sales_plan_id' => 1,
                'date' => Carbon::parse('2026-03-25'),
                'amount' => 10000,
                'details' => '1',
                'remarks' => 'Not Paid Yet',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sales_plan_id' => 1,
                'date' => Carbon::parse('2026-03-25'),
                'amount' => 10000,
                'details' => '1',
                'remarks' => 'Not Paid Yet',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sales_plan_id' => 1,
                'date' => Carbon::parse('2026-06-25'),
                'amount' => 10000,
                'details' => '1',
                'remarks' => 'Not Paid Yet',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

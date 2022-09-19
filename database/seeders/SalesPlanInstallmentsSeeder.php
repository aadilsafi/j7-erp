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
        (new SalesPlanInstallments())->insert([
            [
                'sales_plan_id' => 1,
                'date' => Carbon::parse('2022-12-18'),
                'amount' => 3399500,
                'paid_amount' => 3399500,
                'remaining_amount' => 0,
                'details' => 'Downpayment',
                'remarks' => 'Paid',
                'installment_order' => 0,
                'status' => 'paid',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sales_plan_id' => 1,
                'date' => Carbon::parse('2022-12-18'),
                'amount' => 1019850,
                'paid_amount' => 1019850,
                'remaining_amount' => 0,
                'details' => '1st Instalment',
                'remarks' => 'Paid',
                'installment_order' => 1,
                'status' => 'paid',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sales_plan_id' => 1,
                'date' => Carbon::parse('2023-03-18'),
                'amount' => 1019850,
                'paid_amount' => 1019850,
                'remaining_amount' => 0,
                'details' => '2nd Instalment',
                'remarks' => 'Paid',
                'installment_order' => 2,
                'status' => 'paid',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sales_plan_id' => 1,
                'date' => Carbon::parse('2023-06-16'),
                'amount' => 1019850,
                'paid_amount' => 1019850,
                'remaining_amount' => 0,
                'details' => '3rd Instalment',
                'remarks' => 'Paid',
                'installment_order' => 3,
                'status' => 'paid',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sales_plan_id' => 1,
                'date' => Carbon::parse('2023-09-14'),
                'amount' => 1019850,
                'paid_amount' => 509925,
                'remaining_amount' => 509925,
                'details' => '4th Instalment',
                'remarks' => 'Partially Paid',
                'installment_order' => 4,
                'status' => 'partially_paid',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sales_plan_id' => 1,
                'date' => Carbon::parse('2023-12-13'),
                'amount' => 1019850,
                'paid_amount' => 0,
                'remaining_amount' => 1019850,
                'details' => '5th Instalment',
                'remarks' => 'UnPaid',
                'installment_order' => 5,
                'status' => 'unpaid',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sales_plan_id' => 1,
                'date' => Carbon::parse('2024-03-12'),
                'amount' => 1019850,
                'paid_amount' => 0,
                'remaining_amount' => 1019850,
                'details' => '6th Instalment',
                'remarks' => 'Unpaid',
                'installment_order' => 6,
                'status' => 'unpaid',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sales_plan_id' => 1,
                'date' => Carbon::parse('2024-06-10'),
                'amount' => 1019850,
                'paid_amount' => 0,
                'remaining_amount' => 1019850,
                'details' => '7th Instalment',
                'remarks' => 'Unpaid',
                'installment_order' => 7,
                'status' => 'paid',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sales_plan_id' => 1,
                'date' => Carbon::parse('2024-09-08'),
                'amount' => 1019850,
                'paid_amount' => 0,
                'remaining_amount' => 1019850,
                'details' => '8th Instalment',
                'remarks' => 'Unpaid',
                'installment_order' => 8,
                'status' => 'unpaid',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sales_plan_id' => 1,
                'date' => Carbon::parse('2024-12-07'),
                'amount' => 1019850,
                'paid_amount' => 0,
                'remaining_amount' => 1019850,
                'details' => '9th Instalment',
                'remarks' => 'Unpaid',
                'installment_order' => 9,
                'status' => 'unpaid',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sales_plan_id' => 1,
                'date' => Carbon::parse('2025-03-07'),
                'amount' => 1019850,
                'paid_amount' => 0,
                'remaining_amount' => 1019850,
                'details' => '10th Instalment',
                'remarks' => 'Unpaid',
                'installment_order' => 10,
                'status' => 'unpaid',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

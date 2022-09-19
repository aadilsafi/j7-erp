<?php

namespace Database\Seeders;

use App\Models\Receipt;
use Illuminate\Database\Seeder;

class ReceiptsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new Receipt())->insert([
            [
                'site_id' => 1,
                'unit_id' => 1,
                'sales_plan_id' => 1,
                'name'=> 'Zain Ali',
                'cnic' => '1234567890123',
                'phone_no' => '0512226044',
                'mode_of_payment' => 'Cash',
                'other_value' => null,
                'pay_order' => null,
                'cheque_no' => null,
                'online_instrument_no' => null,
                'drawn_on_bank' => null,
                'transaction_date' => now(),
                'amount_in_words' => 'Three Million Three Hundred Ninety-Nine Thousand five hundred Only',
                'amount_in_numbers' => 3399500,
                'purpose' => 'Downpayment',
                'installment_number' => '["Downpayment"]',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'site_id' => 1,
                'unit_id' => 1,
                'sales_plan_id' => 1,
                'name'=> 'Zain Ali',
                'cnic' => '1234567890123',
                'phone_no' => '0512226044',
                'mode_of_payment' => 'Cash',
                'other_value' => null,
                'pay_order' => null,
                'cheque_no' => null,
                'online_instrument_no' => null,
                'drawn_on_bank' => null,
                'transaction_date' => now(),
                'amount_in_words' => 'One Million Nineteen Thousand Eight Hundred Fifty Only',
                'amount_in_numbers' => 1019850,
                'purpose' => '1st Installment',
                'installment_number' => '["1st Installment"]',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'site_id' => 1,
                'unit_id' => 1,
                'sales_plan_id' => 1,
                'name'=> 'Zain Ali',
                'cnic' => '1234567890123',
                'phone_no' => '0512226044',
                'mode_of_payment' => 'Cash',
                'other_value' => null,
                'pay_order' => null,
                'cheque_no' => null,
                'online_instrument_no' => null,
                'drawn_on_bank' => null,
                'transaction_date' => now(),
                'amount_in_words' => 'One Million Nineteen Thousand Eight Hundred Fifty Only',
                'amount_in_numbers' => 1019850,
                'purpose' => '2nd Installment',
                'installment_number' => '["2nd Installment"]',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'site_id' => 1,
                'unit_id' => 1,
                'sales_plan_id' => 1,
                'name'=> 'Zain Ali',
                'cnic' => '1234567890123',
                'phone_no' => '0512226044',
                'mode_of_payment' => 'Cash',
                'other_value' => null,
                'pay_order' => null,
                'cheque_no' => null,
                'online_instrument_no' => null,
                'drawn_on_bank' => null,
                'transaction_date' => now(),
                'amount_in_words' => 'One Million Nineteen Thousand Eight Hundred Fifty Only',
                'amount_in_numbers' => 1019850,
                'purpose' => '3rd Installment',
                'installment_number' => '["3rd Installment"]',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'site_id' => 1,
                'unit_id' => 1,
                'sales_plan_id' => 1,
                'name'=> 'Zain Ali',
                'cnic' => '1234567890123',
                'phone_no' => '0512226044',
                'mode_of_payment' => 'Cash',
                'other_value' => null,
                'pay_order' => null,
                'cheque_no' => null,
                'online_instrument_no' => null,
                'drawn_on_bank' => null,
                'transaction_date' => now(),
                'amount_in_words' => 'Five Hundred Nine Thousand Nine Hundred Twenty-Five Only',
                'amount_in_numbers' => 509925,
                'purpose' => '4th Installment',
                'installment_number' => '["4th Installment"]',
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempReceipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'doc_no',
        'sales_plan_doc_no',
        'mode_of_payment',
        'cheque_no',
        'bank_name',
        'bank_acount_number',
        'online_transaction_no',
        'transaction_date',
        'other_payment_mode_value',
        'amount',
        'status',
        'image_url',
        'installment_no',
        'discounted_amount',
    ];
}

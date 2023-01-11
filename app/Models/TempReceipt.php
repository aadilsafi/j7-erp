<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempReceipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit_short_label',
        'doc_no',
        'stakeholder_cnic',
        'total_price',
        'discounted_amount',
        'down_payment_total',
        'validity',
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
        'installment_no'
    ];
}

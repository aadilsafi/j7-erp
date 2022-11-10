<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempSalePlanInstallment extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit_short_label',
        'stakeholder_cnic',
        'total_price',
        'down_payment_total',
        'validity',
        'type',
        'label',
        'due_date',
        'installment_no',
        'total_amount',
    ];
}

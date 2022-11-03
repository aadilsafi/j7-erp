<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempSalePlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit_short_label',
        'stakeholder_cnic',
        'unit_price',
        'total_price',
        'discount_percentage',
        'discount_total',
        'down_payment_percentage',
        'down_payment_total',
        'lead_source',
        'validity',
        'status',
        'comment',
        'approved_date'
    ];
}

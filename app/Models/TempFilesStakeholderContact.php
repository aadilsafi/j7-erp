<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempFilesStakeholderContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit_short_label',
        'stakeholder_cnic',
        'total_price',
        'down_payment_total',
        'sales_plan_approval_date',
        'conatct_cnic',
        'kin_cnic',
    ];
}

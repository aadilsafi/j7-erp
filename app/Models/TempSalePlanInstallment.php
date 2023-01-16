<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempSalePlanInstallment extends Model
{
    use HasFactory;

    protected $fillable = [
        'sales_plan_doc_no',
        'type',
        'label',
        'due_date',
        'installment_no',
        'total_amount',
    ];
}

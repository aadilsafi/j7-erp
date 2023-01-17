<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempSalesPlanAdditionalCost extends Model
{
    use HasFactory;

    protected $fillable = [
        'sales_plan_doc_no',
        'additional_costs_name',
        'percentage',
        'total_amount',
    ];
}

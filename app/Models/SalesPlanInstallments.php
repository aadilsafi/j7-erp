<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\{HasFactory};
use Illuminate\Database\Eloquent\{Model, SoftDeletes};

class SalesPlanInstallments extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sales_plan_id',
        'details',
        'date',
        'amount',
        'paid_amount',
        'remaining_amount',
        'remarks',
    ];

    protected $casts = [
        'sales_plan_id' => 'integer',
        'details' => 'string',
        'date' => 'string',
        'amount' => 'double',
        'paid_amount' => 'double',
        'remaining_amount' => 'double',
        'remarks' => 'string',
    ];

    public function salesPlan()
    {
        return $this->belongsTo(SalesPlan::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\{HasFactory};
use Illuminate\Database\Eloquent\{Model};

class SalesPlanAdditionalCost extends Model
{
    use HasFactory;

    protected $fillable = [
        'sales_plan_id',
        'additional_cost_id',
        'percentage',
        'amount',
    ];

    protected $casts = [
        'percentage' => 'float',
        'amount' => 'float',
    ];

    public function salesPlan()
    {
        return $this->belongsTo(SalesPlan::class);
    }

    public function additionalCost()
    {
        return $this->belongsTo(AdditionalCost::class);
    }
}

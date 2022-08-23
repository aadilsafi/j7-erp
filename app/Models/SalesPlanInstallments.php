<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\{HasFactory};
use Illuminate\Database\Eloquent\{Model, SoftDeletes};

class SalesPlanInstallments extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sales_plan_id',
        'date',
        'amount',
        'details',
        'remarks',
    ];

    protected $casts = [
        'amount' => 'float',
        'details' => 'string',
        'remarks' => 'string',
    ];

    public function salesPlan()
    {
        return $this->belongsTo(SalesPlan::class);
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\{HasFactory};
use Illuminate\Database\Eloquent\{Model};
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class SalesPlanAdditionalCost extends Model
{
    use HasFactory, LogsActivity;

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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName(get_class($this))->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    public function salesPlan()
    {
        return $this->belongsTo(SalesPlan::class);
    }

    public function additionalCost()
    {
        return $this->belongsTo(AdditionalCost::class);
    }
}

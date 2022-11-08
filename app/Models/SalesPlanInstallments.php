<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\{HasFactory};
use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class SalesPlanInstallments extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'sales_plan_id',
        'details',
        'date',
        'amount',
        'type',
        'paid_amount',
        'remaining_amount',
        'is_imported',
        'remarks',
        'installment_order',
        'status',
        'last_paid_at',
    ];

    protected $casts = [
        'sales_plan_id' => 'integer',
        'details' => 'string',
        'date' => 'string',
        'type' => 'string',
        'amount' => 'double',
        'paid_amount' => 'double',
        'remaining_amount' => 'double',
        'remarks' => 'string',
        'installment_order' => 'integer',
        'status' => 'string',
        'last_paid_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName(get_class($this))->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    public function salesPlan()
    {
        return $this->belongsTo(SalesPlan::class);
    }
}

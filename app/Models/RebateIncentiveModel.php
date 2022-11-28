<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class RebateIncentiveModel extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'site_id',
        'unit_id',
        'stakeholder_id',
        'stakeholder_data',
        'unit_data',
        'deal_type',
        'commision_percentage',
        'commision_total',
        'is_for_dealer_incentive',
        'status',
        'comments',
        'dealer_id',
        'bank_id',
        'mode_of_payment',
        'other_value',
        'cheque_no',
        'online_instrument_no',
        'transaction_date',
        'created_date',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName(get_class($this))->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function stakeholder()
    {
        return $this->belongsTo(Stakeholder::class);
    }
    public function dealer()
    {
        return $this->belongsTo(Stakeholder::class, 'dealer_id', 'id');
    }

    public function CustomFieldValues()
    {
        return $this->morphMany(CustomFieldValue::class, 'modelable');
    }
}

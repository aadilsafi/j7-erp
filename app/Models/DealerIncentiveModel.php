<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class DealerIncentiveModel extends Model
{
    use HasFactory, LogsActivity ,SoftDeletes;

    protected $fillable = [
        'site_id',
        'dealer_id',
        'dealer_data',
        'dealer_incentive',
        'total_unit_area',
        'total_dealer_incentive',
        'status',
        'unit_IDs',
        'comments',
        'serial_no',
        'user_id',
        'checked_date',
        'checked_by',
        'approved_by',
        'approved_date',
        'reverted_by',
        'reverted_date',
        'cheque_active_by',
        'cheque_active_date',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName(get_class($this))->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    public function dealer()
    {
        return $this->belongsTo(Stakeholder::class,'dealer_id','id');
    }

    public function CustomFieldValues()
    {
        return $this->morphMany(CustomFieldValue::class, 'modelable');
    }
}

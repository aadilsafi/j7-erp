<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class DealerIncentiveModel extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'site_id',
        'dealer_id',
        'dealer_data',
        'dealer_incentive',
        'total_unit_area',
        'total_dealer_incentive',
        'status',
        'comments',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName(get_class($this))->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }
}

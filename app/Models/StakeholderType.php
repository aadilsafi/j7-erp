<?php

namespace App\Models;

use App\Models\Stakeholder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class StakeholderType extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'stakeholder_id',
        'type',
        'stakeholder_code',
        'status',
        'receivable_account',
        'payable_account',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName(get_class($this))->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    public function stakeholder()
    {
        return $this->belongsTo(Stakeholder::class);
    }
}

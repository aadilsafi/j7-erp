<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class UserBatch extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'site_id',
        'user_id',
        'job_batch_id',
        'actions',
        'batch_status',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName(get_class($this))->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jobBatch()
    {
        return $this->belongsTo(JobBatch::class);
    }
}

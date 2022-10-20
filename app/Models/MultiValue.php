<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class MultiValue extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'value',
        'type',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName(get_class($this))->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    protected $casts = [
        'value' => 'string',
        'type' => 'string',
    ];

    public function multivalueable()
    {
        return $this->morphTo();
    }
}

<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccountingStartingCode extends Model
{
    use HasFactory,LogsActivity;

    protected $fillable =  [
        'site_id',
        'model',
        'level_code',
        'starting_code',
        'level',
        'status',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName(get_class($this))->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    protected $cast =  [
        'site_id' => 'intiger',
        'model' => 'string',
        'level_code'=> 'string',
        'starting_code' => 'string',
        'level' => 'intiger',
        'status' => 'boolean',
    ];
}

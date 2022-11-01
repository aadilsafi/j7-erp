<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;


class Bank extends Model
{
    use HasFactory ,SoftDeletes,LogsActivity;

    protected $fillable = [
        'site_id',
        'name',
        'slug',
        'account_number',
        'branch',
        'branch_code',
        'address',
        'contact_number',
        'status',
        'comments',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName(get_class($this))->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

}

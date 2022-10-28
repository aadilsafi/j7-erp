<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class AccountLedger extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'site_id',
        'account_head_code',
        'account_action_id',
        'credit',
        'debit',
        'balance',
        'nature_of_account',
        'status',
    ];

    protected $casts = [
        'site_id' => 'integer',
        'account_action_id' => 'integer',
        'credit' => 'double',
        'debit' => 'double',
        'balance' => 'double',
        'status' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName(get_class($this))->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class AccountHead extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $primaryKey = 'code';

    protected $fillable = [
        'site_id',
        'code',
        'name',
        'level',
    ];

    protected $casts = [
        'level' => 'integer',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName(get_class($this))->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    public function accountLedgers()
    {
        return $this->HasMany(AccountLedger::class);
    }

    public function modelable()
    {
        return $this->morphTo();
    }
}

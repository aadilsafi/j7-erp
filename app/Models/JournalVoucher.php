<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class JournalVoucher extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName(get_class($this))->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    protected $fillable = [
        'site_id',
        'user_id',
        'name',
        'account_head_code',
        'account_number',
        'serial_number',
        'voucher_date',
        'voucher_type',
        'voucher_amount',
        'status',
        'remarks',
        'comments',
        'created_date',
        'approved_by',
        'approved_date',
        'checked_by',
        'checked_date',
        'total_debit',
        'total_credit',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class JournalVoucherEntry extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName(get_class($this))->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    protected $fillable = [
        'site_id',
        'user_id',
        'account_head_code',
        'journal_voucher_id',
        'serial_number',
        'account_number',
        'credit',
        'debit',
        'balance',
        'remarks',
        'status',
        'comments',
        'created_date',
        'approved_by',
        'approved_date',
        'tax_amount',
        'total_amount',
        'checked_by',
        'checked_date',
        'total_debit',
        'total_credit',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function accountHead()
    {
        return $this->belongsTo(AccountHead::class, 'account_head_code', 'code');
    }
}

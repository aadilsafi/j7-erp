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
        'sales_plan_id',
        'receipt_id',
        'status',
    ];

    protected $casts = [
        'site_id' => 'integer',
        'sales_plan_id' => 'integer',
        'receipt_id' => 'integer',
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

    public function accountActions()
    {
        return $this->belongsTo(AccountAction::class,'account_action_id','id');
    }

    public function accountHead()
    {
        return $this->belongsTo(AccountHead::class,'account_head_code','code');
    }

    public function salesPlan()
    {
        return $this->belongsTo(SalesPlan::class,'sales_plan_id','id')->with('unit','unit.floor');
    }

    public function receipt()
    {
        return $this->belongsTo(Receipt::class,'receipt_id','id');
    }
}

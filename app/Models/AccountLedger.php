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
        'origin_name',
        'origin_number',
        'credit',
        'debit',
        'balance',
        'nature_of_account',
        'sales_plan_id',
        'receipt_id',
        'file_refund_id',
        'file_resale_id',
        'file_buyback_id',
        'file_cancellation_id',
        'file_title_transfer_id',
        'status',
    ];

    protected $casts = [
        'account_head_code'=> 'string',
        'site_id' => 'integer',
        'origin_name'=> 'string',
        'origin_number'=> 'string',
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
        return $this->belongsTo(AccountAction::class, 'account_action_id', 'id');
    }

    public function accountHead()
    {
        return $this->belongsTo(AccountHead::class, 'account_head_code', 'code');
    }

    public function salesPlan()
    {
        return $this->belongsTo(SalesPlan::class, 'sales_plan_id', 'id')->with('unit', 'unit.floor' ,'stakeholder');
    }

    public function receipt()
    {
        return $this->belongsTo(Receipt::class, 'receipt_id', 'id');
    }
}

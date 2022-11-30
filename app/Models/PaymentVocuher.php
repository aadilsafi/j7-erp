<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class PaymentVocuher extends Model
{
    use HasFactory,LogsActivity;

    protected $fillable = [
        'site_id',
        'user_id',
        'customer_id',
        'dealer_id',
        'vendor_id',
        'sales_plan_id',
        'receipt_id',
        'file_refund_id',
        'file_resale_id',
        'file_buyback_id',
        'file_cancellation_id',
        'file_title_transfer_id',
        'rebate_incentive_id',
        'dealer_incentive_id',
        'customer_dealer_vendor_details',
        'name',
        'representative',
        'business_type',
        'identity_number',
        'ntn',
        'tax_status',
        'bussiness_address',
        'transaction_details',
        'description',
        'account_payable',
        'expense_account',
        'total_payable_amount',
        'advance_given',
        'discount_recevied',
        'net_payable',
        'payment_mode',
        'transaction_mode',
        'bank_name',
        'account_number',
        'receiving_date',
        'approved_date',
        'approved_by',
        'comments',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName(get_class($this))->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

}

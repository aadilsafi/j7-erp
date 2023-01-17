<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class PaymentVocuher extends Model implements HasMedia
{
    use HasFactory, LogsActivity,InteractsWithMedia;

    protected $fillable = [
        'site_id',
        'doc_no',
        'user_id',
        'customer_id',
        'dealer_id',
        'vendor_id',
        // 'sales_plan_id',
        // 'receipt_id',
        // 'file_refund_id',
        // 'file_resale_id',
        // 'file_buyback_id',
        // 'file_cancellation_id',
        // 'file_title_transfer_id',
        // 'rebate_incentive_id',
        // 'dealer_incentive_id',
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
        'stakeholder_type',
        'customer_ap_account',
        'dealer_ap_account',
        'vendor_ap_account',
        'amount_to_be_paid',
        'status',
        'bank_id',
        'serial_no',
        'cheque_status',
        'checked_date',
        'checked_by',
        'reverted_by',
        'reverted_date',
        'cheque_active_by',
        'cheque_active_date',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName(get_class($this))->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    public function vendorStackholder()
    {
        return $this->belongsTo(Stackholder::class, 'vender_id');
    }
    public function dealerStackholder()
    {
        return $this->belongsTo(Stackholder::class, 'dealer_id');
    }
    public function customerStackholder()
    {
        return $this->belongsTo(Stackholder::class, 'customer_id');
    }
}

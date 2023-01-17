<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class InvsetorDealsReceipt extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, LogsActivity, InteractsWithMedia;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName(get_class($this))->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    protected $fillable = [
        'site_id',
        'user_id',
        'investor_deal_id',
        'investor_id',
        'serial_number',
        'doc_no',
        'total_received_amount',
        'total_payable_amount',
        'created_date',
        'checked_by',
        'checked_date',
        'active_by',
        'active_date',
        'bounced_by',
        'bounced_by_date',
        'reverted_by',
        'reverted_date',
        'jve_number',
        'status',
        'remarks',
        'name',
        'cnic',
        'phone_no',
        'mode_of_payment',
        'other_value',
        'cheque_no',
        'online_instrument_no',
        'transaction_date',
        'discounted_amount',
        'other_purpose',
        'bank_details',
        'investor_ar_account',
        'customer_ap_amount',
        'customer_ap_account',
        'dealer_ap_amount',
        'dealer_ap_account',
        'vendor_ap_amount',
        'vendor_ap_account',
        'investor_ap_amount',
        'investor_ap_account',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function investorDeal()
    {
        return $this->belongsTo(StakeholderInvestor::class,'investor_deal_id','id');
    }

    public function investor()
    {
        return $this->belongsTo(Stakeholder::class,'investor_id','id');
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }
}

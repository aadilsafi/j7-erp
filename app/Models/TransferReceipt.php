<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\StakeholderType;
use App\Utils\Enums\StakeholderTypeEnum;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class TransferReceipt extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia, LogsActivity;

    protected $fillable = [
        'site_id',
        'serial_no',
        'unit_id',
        'file_id',
        'file_title_transfer_id',
        'stakeholder_id',
        'mode_of_payment',
        'other_value',
        'pay_order',
        'cheque_no',
        'online_transaction_no',
        'drawn_on_bank',
        'bank_id',
        'transaction_date',
        'discounted_amount',
        'amount_in_words',
        'attachment',
        'amount',
        'comments',
        'status',
        'bank_details',
        'created_date',
        'customer_ar_account',
        'customer_ap_amount',
        'customer_ap_account',
        'dealer_ap_amount',
        'dealer_ap_account',
        'vendor_ap_amount',
        'vendor_ap_account',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName(get_class($this))->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    public function stakeholder()
    {
        return $this->belongsTo(Stakeholder::class);
    }
}

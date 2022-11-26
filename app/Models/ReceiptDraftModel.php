<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;

class ReceiptDraftModel extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, LogsActivity;

    protected $fillable = [
        'site_id',
        'unit_id',
        'sales_plan_id',
        'name',
        'cnic',
        'mode_of_payment',
        'cheque_no',
        'online_instrument_no',
        'drawn_on_bank',
        'transaction_date',
        'amount_in_words',
        'amount_in_numbers',
        'purpose',
        'installment_number',
        'phone_no',
        'other_value',
        'pay_order',
        'amount_received',
        'attachment',
        'comments',
        'status',
        'bank_details',
        'created_date',
        'discounted_amount',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName(get_class($this))->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

}

<?php

namespace App\Models;

use App\Models\Site;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Receipt extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, LogsActivity;

    protected $fillable = [
        'site_id',
        'unit_id',
        'sales_plan_id',
        'bank_id',
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
        'is_imported',
        'comments',
        'status',
        'bank_details',
        'created_date',
        'discounted_amount',
        'serial_no',
        'customer_ar_account',
        'customer_ap_amount',
        'customer_ap_account',
        'dealer_ap_amount',
        'dealer_ap_account',
        'vendor_ap_amount',
        'vendor_ap_account',
    ];

    public $rules = [
        'receipts.item_idunit_id' => 'required',
        'receipts.mode_of_payment' => 'required',
        'receipts.amount_in_numbers' => 'required',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            $model->serial_no = "RV-" . $model->serial_no;
            $model->save();
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName(get_class($this))->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function salesPlan()
    {
        return $this->belongsTo(SalesPlan::class);
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function CustomFieldValues()
    {
        return $this->morphMany(CustomFieldValue::class, 'modelable');
    }
}

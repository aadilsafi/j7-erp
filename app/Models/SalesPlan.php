<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class SalesPlan extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'unit_id',
        'user_id',
        'stakeholder_id',
        'kin_id',
        'stakeholder_data',
        'unit_price',
        'total_price',
        'discount_percentage',
        'discount_total',
        'down_payment_percentage',
        'down_payment_total',
        'lead_source_id',
        'validity',
        'is_imported',
        'comments',
        'status',
        'approved_date',
        'cancel',
        'created_date',
    ];

    protected $casts = [
        'stakeholder_data' => 'array',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName(get_class($this))->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function stakeholder()
    {
        return $this->belongsTo(Stakeholder::class);
    }

    public function additionalCosts()
    {
        return $this->belongsToMany(AdditionalCost::class, 'sales_plan_additional_costs')
            ->withPivot('amount', 'percentage');
    }

    public function installments()
    {
        return $this->hasMany(SalesPlanInstallments::class)->orderBy('installment_order');
    }

    public function PaidorPartiallyPaidInstallments()
    {
        return $this->hasMany(SalesPlanInstallments::class)->where('status','paid')->orWhere('status','partially_paid')->orderBy('installment_order', 'asc');
    }

    public function unPaidInstallments()
    {
        return $this->hasMany(SalesPlanInstallments::class)->where('status','unpaid')->orWhere('status','partially_paid')->orderBy('installment_order', 'asc');
    }

    public function leadSource()
    {
        return $this->belongsTo(LeadSource::class);
    }

    public function receipts()
    {
        return $this->hasMany(Receipt::class);
    }
}

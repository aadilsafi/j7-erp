<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};

class SalesPlan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'unit_id',
        'user_id',
        'stakeholder_id',
        'unit_price',
        'total_price',
        'discount_percentage',
        'discount_total',
        'down_payment_percentage',
        'down_payment_total',
        'lead_source_id',
        'validity',
        'comments',
        'status',
    ];

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
        return $this->hasMany(SalesPlanInstallments::class);
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
}

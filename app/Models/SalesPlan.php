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
        'lead_source',
        'validity',
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
        return $this->hasMany(SalesPlanAdditionalCost::class);
    }

    public function installments()
    {
        return $this->hasMany(SalesPlanInstallments::class);
    }
}

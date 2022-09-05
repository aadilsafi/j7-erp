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
        'validity',
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

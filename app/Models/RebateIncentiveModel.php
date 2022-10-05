<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RebateIncentiveModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_id',
        'unit_id',
        'stakeholder_id',
        'stakeholder_data',
        'unit_data',
        'deal_type',
        'commision_percentage',
        'commision_total',
        'status',
        'comments',
        'dealer_id',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function stakeholder()
    {
        return $this->belongsTo(Stakeholder::class);
    }

}
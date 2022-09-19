<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UnitStakeholder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'unit_id',
        'stakeholder_id',
    ];

    protected $casts = [
        'unit_id' => 'integer',
        'stakeholder_id' => 'integer',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function stakeholder()
    {
        return $this->belongsTo(Stakeholder::class);
    }
}

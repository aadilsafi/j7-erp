<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StakeholderContact extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'stakeholder_id',
        'full_name',
        'father_name',
        'occupation',
        'designation',
        'cnic',
        'ntn',
        'contact',
        'address',
    ];

    public function stakeholder()
    {
        return $this->belongsTo(Stakeholder::class);
    }
}

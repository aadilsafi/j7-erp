<?php

namespace App\Models;

use App\Models\Stakeholder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StakeholderType extends Model
{
    use HasFactory;

    protected $fillable = [
        'stakeholder_id',
        'type',
        'stakeholder_code',
        'status',
        'created_at',
        'updated_at',
    ];

    public function stakeholder()
    {
        return $this->belongsTo(Stakeholder::class);
    }
}

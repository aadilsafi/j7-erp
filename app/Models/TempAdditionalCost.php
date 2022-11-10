<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempAdditionalCost extends Model
{
    use HasFactory;

    protected $fillable = [
        'additional_costs_name',
        'site_percentage',
        'floor_percentage',
        'unit_percentage',
        'is_sub_types',
        'parent_type_name'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'floor_short_label',
        'name',
        'width',
        'length',
        'unit_short_label',
        'net_area',
        'gross_area',
        'price_sqft',
        'total_price',
        'unit_type_slug',
        'status',
        'parent_unit_short_label',
        'is_corner',
        'is_facing',
        'additional_costs_name',
    ];	

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempUnitType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'unit_type_slug',
        'parent_type_name',
    ];
}

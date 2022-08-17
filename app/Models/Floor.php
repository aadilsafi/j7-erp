<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use PhpOffice\PhpSpreadsheet\Chart\Chart;

class Floor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'floor_area',
        'site_id',
        'order',
        'short_label',
        'active',
    ];

    protected $casts = [
        'floor_area' => 'float',
        'site_id' => 'integer',
        'order' => 'integer',
        'short_label' => 'string',
        'active' => 'boolean',
    ];

    public $rules = [
        'name' => 'required|string|max:255',
        'floor_area' => 'required|numeric',
        'floor_order' => 'nullable|numeric',
        'short_label' => 'required|string|max:5',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function units()
    {
        return $this->hasMany(Unit::class);
    }
}

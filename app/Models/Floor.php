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
        'width',
        'length',
        'site_id',
        'order',
        'prefix',
        'active',
    ];

    protected $casts = [
        'width' => 'float',
        'length' => 'float',
        'site_id' => 'integer',
        'order' => 'integer',
        'prefix' => 'string',
        'active' => 'boolean',
    ];

    public $rules = [
        'name' => 'required|string|max:255',
        'width' => 'required|numeric',
        'length' => 'required|numeric',
        'floor_order' => 'nullable|integer',
        'prefix' => 'required|string|max:5',
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

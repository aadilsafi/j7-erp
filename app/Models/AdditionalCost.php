<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class AdditionalCost extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'site_id',
        'name',
        'slug',
        'parent_id',
        'has_child',
        'site_percentage',
        'applicable_on_site',
        'floor_percentage',
        'applicable_on_floor',
        'unit_percentage',
        'applicable_on_unit',
        'is_imported',
    ];

    protected $casts = [
        'site_id' => 'integer',
        'parent_id' => 'integer',
        'has_child' => 'boolean',
        'site_percentage' => 'float',
        'applicable_on_site' => 'boolean',
        'floor_percentage' => 'float',
        'applicable_on_floor' => 'boolean',
        'unit_percentage' => 'float',
        'applicable_on_unit' => 'boolean',
    ];

    public $rules = [
        'name' => 'required|string|min:1|max:255',
        // 'slug' => 'required|alpha_dash|min:1|max:255|unique:additional_costs,slug',
        'additionalCost' => 'required|integer',
        'has_child' => 'boolean|in:0,1',
        'applicable_on_site' => 'required|boolean|in:0,1',
        'site_percentage' => 'required_if:applicable_on_site,1|numeric',
        'applicable_on_floor' => 'required|boolean|in:0,1',
        'floor_percentage' => 'required_if:applicable_on_floor,1|numeric',
        'applicable_on_unit' => 'required|boolean|in:0,1',
        'unit_percentage' => 'required_if:applicable_on_unit,1|numeric',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName(get_class($this))->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }
}

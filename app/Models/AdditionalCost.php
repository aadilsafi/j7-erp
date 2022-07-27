<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\AdditionalCost
 *
 * @property int $id
 * @property int $site_id
 * @property string|null $name
 * @property string $slug
 * @property int $parent_id
 * @property bool $has_child
 * @property float $site_percentage
 * @property bool $applicable_on_site
 * @property float $floor_percentage
 * @property bool $applicable_on_floor
 * @property float $unit_percentage
 * @property bool $applicable_on_unit
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|AdditionalCost[] $children
 * @property-read int|null $children_count
 * @property-read AdditionalCost|null $parent
 * @property-read \App\Models\Site $site
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalCost newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalCost newQuery()
 * @method static \Illuminate\Database\Query\Builder|AdditionalCost onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalCost query()
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalCost whereApplicableOnFloor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalCost whereApplicableOnSite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalCost whereApplicableOnUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalCost whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalCost whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalCost whereFloorPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalCost whereHasChild($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalCost whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalCost whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalCost whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalCost whereSiteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalCost whereSitePercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalCost whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalCost whereUnitPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalCost whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|AdditionalCost withTrashed()
 * @method static \Illuminate\Database\Query\Builder|AdditionalCost withoutTrashed()
 * @mixin \Eloquent
 */
class AdditionalCost extends Model
{
    use HasFactory, SoftDeletes;

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
        'slug' => 'required|alpha_dash|min:1|max:255|unique:additional_costs,slug',
        'additionalCost' => 'required|integer',
        'has_child' => 'boolean|in:0,1',
        'applicable_on_site' => 'required|boolean|in:0,1',
        'site_percentage' => 'required_if:applicable_on_site,1|numeric',
        'applicable_on_floor' => 'required|boolean|in:0,1',
        'floor_percentage' => 'required_if:applicable_on_floor,1|numeric',
        'applicable_on_unit' => 'required|boolean|in:0,1',
        'unit_percentage' => 'required_if:applicable_on_unit,1|numeric',
    ];

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

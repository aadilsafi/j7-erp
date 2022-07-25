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

    public $rules = [
        'site_id' => 'required|integer',
        'name' => 'required|string|min:1|max:255',
        'slug' => 'required|string|min:1|max:255|unique:additional_costs,slug',
        'parent_id' => 'required|integer',
        'has_child' => 'required|boolean',
        'site_percentage' => 'required|numeric',
        'applicable_on_site' => 'required|boolean',
        'floor_percentage' => 'required|numeric',
        'applicable_on_floor' => 'required|boolean',
        'unit_percentage' => 'required|numeric',
        'applicable_on_unit' => 'required|boolean',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function parent()
    {
        return $this->belongsTo(AdditionalCost::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(AdditionalCost::class, 'parent_id');
    }
}

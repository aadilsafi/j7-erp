<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SiteConfigration
 *
 * @property int $id
 * @property int $site_id
 * @property int $site_max_floors
 * @property string $floor_prefix
 * @property int $unit_number_digits
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Models\Site $site
 * @method static \Illuminate\Database\Eloquent\Builder|SiteConfigration newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SiteConfigration newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SiteConfigration query()
 * @method static \Illuminate\Database\Eloquent\Builder|SiteConfigration whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SiteConfigration whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SiteConfigration whereFloorPrefix($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SiteConfigration whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SiteConfigration whereSiteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SiteConfigration whereSiteMaxFloors($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SiteConfigration whereUnitNumberDigits($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SiteConfigration whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SiteConfigration extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $requestRules = [
        'site_id' => 'required',
        'site_max_floors' => 'required',
        'floor_prefix' => 'required',
        'unit_number_digits' => 'required',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }
}

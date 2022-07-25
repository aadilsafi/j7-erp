<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SiteConfigrations
 *
 * @property int $id
 * @property int $site_id
 * @property int $site_max_floors
 * @property string $floor_prefix
 * @property int $unit_number_digits
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|SiteConfigrations newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SiteConfigrations newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SiteConfigrations query()
 * @method static \Illuminate\Database\Eloquent\Builder|SiteConfigrations whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SiteConfigrations whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SiteConfigrations whereFloorPrefix($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SiteConfigrations whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SiteConfigrations whereSiteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SiteConfigrations whereSiteMaxFloors($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SiteConfigrations whereUnitNumberDigits($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SiteConfigrations whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SiteConfigration extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_id',
        'site_max_floors',
        'floor_prefix',
        'unit_number_digits',
    ];

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

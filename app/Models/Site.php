<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Site
 *
 * @property int $id
 * @property string $name
 * @property int $city_id
 * @property string $address
 * @property float $area_width
 * @property float $area_length
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Models\City $city
 * @property-read \App\Models\Country|null $country
 * @method static \Illuminate\Database\Eloquent\Builder|Site newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Site newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Site query()
 * @method static \Illuminate\Database\Eloquent\Builder|Site whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Site whereAreaLength($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Site whereAreaWidth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Site whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Site whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Site whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Site whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Site whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Site whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Site extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'city_id',
        'address',
        'area_width',
        'area_length',
    ];

    public $requestRules = [
        'name'          => 'required',
        'city'          => 'required',
        'address'       => 'required',
        'area_width'    => 'required',
        'area_length'   => 'required',
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function country()
    {
        return $this->hasOneThrough(Country::class, State::class, 'country_id', 'id');
    }

    public function floors()
    {
        return $this->hasMany(Floor::class);
    }

    public function units()
    {
        return $this->hasManyThrough(Unit::class, Floor::class);
    }

    public function siteConfiguration()
    {
        return $this->hasOne(SiteConfigration::class);
    }

}

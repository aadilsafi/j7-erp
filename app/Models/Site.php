<?php

namespace App\Models;

use App\Models\Receipt;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function additionalCosts()
    {
        return $this->hasMany(AdditionalCost::class);
    }

    public function units()
    {
        return $this->hasManyThrough(Unit::class, Floor::class);
    }

    public function siteConfiguration()
    {
        return $this->hasOne(SiteConfigration::class, 'site_id');
    }

    public function statuses()
    {
        return $this->belongsToMany(Status::class)->withPivot('percentage');
    }

    public function types()
    {
        return $this->hasMany(Type::class);
    }

    public function receipts()
    {
        return $this->hasMany(Receipt::class);
    }

}

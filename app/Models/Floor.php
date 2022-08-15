<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use PhpOffice\PhpSpreadsheet\Chart\Chart;

/**
 * App\Models\Floor
 *
 * @property int $id
 * @property string $name
 * @property float $width
 * @property float $length
 * @property string $prefix
 * @property int $order
 * @property int $site_id
 * @property bool $active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Site $site
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Unit[] $units
 * @property-read int|null $units_count
 * @method static \Illuminate\Database\Eloquent\Builder|Floor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Floor newQuery()
 * @method static \Illuminate\Database\Query\Builder|Floor onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Floor query()
 * @method static \Illuminate\Database\Eloquent\Builder|Floor whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Floor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Floor whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Floor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Floor whereLength($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Floor whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Floor whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Floor wherePrefix($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Floor whereSiteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Floor whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Floor whereWidth($value)
 * @method static \Illuminate\Database\Query\Builder|Floor withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Floor withoutTrashed()
 * @mixin \Eloquent
 */
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

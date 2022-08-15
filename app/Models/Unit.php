<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Unit
 *
 * @property int $id
 * @property int $floor_id
 * @property string|null $name
 * @property float $width
 * @property float $length
 * @property int $unit_number
 * @property string|null $floor_unit_number
 * @property float $net_area
 * @property float $gross_area
 * @property float $price_sqft
 * @property float $total_price
 * @property int $type_id
 * @property int $status_id
 * @property bool $active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property bool $is_corner
 * @property int|null $corner_id
 * @property bool $is_facing
 * @property int|null $facing_id
 * @property-read \App\Models\User|null $agent
 * @property-read \App\Models\AdditionalCost|null $corner
 * @property-read \App\Models\AdditionalCost|null $facing
 * @property-read \App\Models\Floor $floor
 * @property-read \App\Models\Status $status
 * @property-read \App\Models\Type $type
 * @method static \Illuminate\Database\Eloquent\Builder|Unit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Unit newQuery()
 * @method static \Illuminate\Database\Query\Builder|Unit onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Unit query()
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereCornerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereFacingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereFloorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereFloorUnitNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereGrossArea($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereIsCorner($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereIsFacing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereLength($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereNetArea($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit wherePriceSqft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereUnitNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereWidth($value)
 * @method static \Illuminate\Database\Query\Builder|Unit withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Unit withoutTrashed()
 * @mixin \Eloquent
 */
class Unit extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'floor_id',
        'name',
        'width',
        'length',
        'unit_number',
        'floor_unit_number',
        'net_area',
        'gross_area',
        'price_sqft',
        'total_price',
        'is_corner',
        'is_facing',
        'facing_id',
        'type_id',
        'status_id',
        'active',
    ];

    protected $casts = [
        'floor_id' => 'integer',
        'name' => 'string',
        'width' => 'float',
        'length' => 'float',
        'unit_number' => 'integer',
        'floor_unit_number' => 'string',
        'net_area' => 'float',
        'gross_area' => 'float',
        'price_sqft' => 'float',
        'total_price' => 'float',
        'is_corner' => 'boolean',
        'corner_id' => 'integer',
        'facing_id' => 'integer',
        'type_id' => 'integer',
        'status_id' => 'integer',
        'active' => 'boolean',
    ];

    public $rules = [
        'name' => 'nullable|string|max:255',
        'width' => 'required|numeric',
        'length' => 'required|numeric',
        'unit_number' => 'nullable|integer',
        // 'floor_unit_number' => 'required|numeric',
        'net_area' => 'required|numeric',
        'gross_area' => 'required|numeric|gte:net_area',
        'price_sqft' => 'required|numeric',
        'is_corner' => 'required|boolean|in:0,1',
        'is_facing' => 'required|boolean|in:0,1',
        'facing_id' => 'required_if:is_facing,1|integer',
        'type_id' => 'required|integer',
        'status_id' => 'required|integer',
        'add_bulk_unit' => 'sometimes|boolean|in:0,1',
        'slider_input_1' => 'required_if:add_bulk_unit,1|integer',
        'slider_input_2' => 'required_if:add_bulk_unit,1|integer',
    ];

    public $ruleMessages = [
        'type_id.required' => 'The Unit Type is required.',
        'corner_id.required_if' => 'The Corner charges field is required when :other is checked.',
        'facing_id.required_if' => 'The Facing charges field is required when :other is checked.',
        'slider_input_1.required_if' => 'The units is required when :other is checked.',
        'slider_input_2.required_if' => 'The units is required when :other is checked.',
    ];

    public function agent()
    {
        return $this->belongsTo(User::class);
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function corner()
    {
        return $this->belongsTo(AdditionalCost::class);
    }

    public function facing()
    {
        return $this->belongsTo(AdditionalCost::class);
    }

    public function floor()
    {
        return $this->belongsTo(Floor::class);
    }
}

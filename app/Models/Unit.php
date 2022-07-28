<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Unit
 *
 * @property int $id
 * @property string|null $name
 * @property float $width
 * @property float $length
 * @property int $unit_number
 * @property int $agent_id
 * @property bool $is_corner
 * @property float $corner_percentage
 * @property float $corner_amount
 * @property bool $is_facing
 * @property int $facing_id
 * @property float $facing_percentage
 * @property float $facing_amount
 * @property int $type_id
 * @property int $status_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|Unit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Unit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Unit query()
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereAgentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereCornerAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereCornerPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereFacingAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereFacingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereFacingPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereIsCorner($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereIsFacing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereLength($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereUnitNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereWidth($value)
 * @mixin \Eloquent
 * @property int $floor_id
 * @property int|null $corner_id
 * @property-read \App\Models\User|null $agent
 * @property-read \App\Models\AdditionalCost|null $corner
 * @property-read \App\Models\AdditionalCost|null $facing
 * @property-read \App\Models\Floor $floor
 * @property-read \App\Models\Status $status
 * @property-read \App\Models\Type $type
 * @method static \Illuminate\Database\Query\Builder|Unit onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereCornerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereFloorId($value)
 * @method static \Illuminate\Database\Query\Builder|Unit withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Unit withoutTrashed()
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
        'price',
        'agent_id',
        'is_corner',
        'corner_id',
        'is_facing',
        'facing_id',
        'type_id',
        'status_id',
    ];

    protected $casts = [
        'floor_id' => 'integer',
        'name' => 'string',
        'width' => 'float',
        'length' => 'float',
        'unit_number' => 'integer',
        'agent_id' => 'integer',
        'price' => 'float',
        'is_corner' => 'boolean',
        'corner_id' => 'integer',
        'is_facing' => 'boolean',
        'facing_id' => 'integer',
        'type_id' => 'integer',
        'status_id' => 'integer',
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

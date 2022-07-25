<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
 */
class Unit extends Model
{
    use HasFactory;
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UnitOwner
 *
 * @property int $id
 * @property int $unit_id
 * @property int $owner_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|UnitOwner newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UnitOwner newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UnitOwner query()
 * @method static \Illuminate\Database\Eloquent\Builder|UnitOwner whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitOwner whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitOwner whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitOwner whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitOwner whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitOwner whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class UnitOwner extends Model
{
    use HasFactory;
}

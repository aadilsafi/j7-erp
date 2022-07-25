<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Facing
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|Facing newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Facing newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Facing query()
 * @method static \Illuminate\Database\Eloquent\Builder|Facing whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Facing whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Facing whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Facing whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Facing whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Facing extends Model
{
    use HasFactory;
}

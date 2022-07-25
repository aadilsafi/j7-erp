<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Floor
 *
 * @property int $id
 * @property string $name
 * @property float $width
 * @property float $length
 * @property int $site_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|Floor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Floor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Floor query()
 * @method static \Illuminate\Database\Eloquent\Builder|Floor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Floor whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Floor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Floor whereLength($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Floor whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Floor whereSiteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Floor whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Floor whereWidth($value)
 * @mixin \Eloquent
 */
class Floor extends Model
{
    use HasFactory;
}

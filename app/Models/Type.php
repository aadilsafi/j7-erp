<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Type
 *
 * @property int $id
 * @property string|null $name
 * @property string $slug
 * @property int $parent_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|Type newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Type newQuery()
 * @method static \Illuminate\Database\Query\Builder|Type onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Type query()
 * @method static \Illuminate\Database\Eloquent\Builder|Type whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Type whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Type whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Type whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Type whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Type whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Type whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Type withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Type withoutTrashed()
 * @mixin \Eloquent
 */
class Type extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'parent_id',
        'slug',
    ];

    public $requestRules = [
        'type' => 'required|numeric',
        'type_name' => 'required|string|min:1|max:255',
        'type_slug' => 'required|string|min:1|max:255|unique:types,slug',
    ];
}

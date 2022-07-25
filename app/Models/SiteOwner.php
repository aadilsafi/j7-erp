<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SiteOwner
 *
 * @property int $id
 * @property int $site_id
 * @property int $owner_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|SiteOwner newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SiteOwner newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SiteOwner query()
 * @method static \Illuminate\Database\Eloquent\Builder|SiteOwner whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SiteOwner whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SiteOwner whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SiteOwner whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SiteOwner whereSiteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SiteOwner whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SiteOwner extends Model
{
    use HasFactory;
}

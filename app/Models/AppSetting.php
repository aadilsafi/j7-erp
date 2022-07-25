<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AppSetting
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|AppSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AppSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AppSetting query()
 * @method static \Illuminate\Database\Eloquent\Builder|AppSetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppSetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppSetting whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AppSetting extends Model
{
    use HasFactory;
}

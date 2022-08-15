<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserBatch
 *
 * @property int $id
 * @property int $site_id
 * @property int $user_id
 * @property string|null $job_batch_id
 * @property string|null $batch_status
 * @property string|null $actions
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|UserBatch newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserBatch newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserBatch query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserBatch whereActions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserBatch whereBatchStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserBatch whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserBatch whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserBatch whereJobBatchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserBatch whereSiteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserBatch whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserBatch whereUserId($value)
 * @mixin \Eloquent
 */
class UserBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_id',
        'user_id',
        'job_batch_id',
        'actions',
        'batch_status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jobBatch()
    {
        return $this->belongsTo(JobBatch::class);
    }
}

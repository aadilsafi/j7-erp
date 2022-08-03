<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'job_batch_id',
        'actions',
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

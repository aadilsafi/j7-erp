<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Team extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'site_id',
        'name',
        'parent_id',
        'has_team'
    ];

    public $rules = [
        'team' => 'required|numeric',
        'team_name' => 'required|string|min:1|max:255',
        'has_team' => 'boolean|in:0,1',
        'user_id' => 'required_if:has_team,0',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName(get_class($this))->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'team_users')->withPivot('site_id');
    }
}

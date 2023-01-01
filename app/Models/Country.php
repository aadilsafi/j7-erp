<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Country extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'name',
        'capital',
        'iso3',
        'phonecode'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName(get_class($this))->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }


    public function cities()
    {
        return $this->hasMany(City::class);
        // return $this->hasManyThrough(City::class,State::class,'country_id','state_id');
    }
    public function states()
    {
        return $this->hasMany(State::class);
    }
}

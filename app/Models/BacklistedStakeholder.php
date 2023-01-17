<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class BacklistedStakeholder extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName(get_class($this))->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    protected $fillable = [
        'name',
        'fatherName',
        'cnic',
        'province',
        'district',
        'country_id',
        'state_id',
        'city_id'
    ];

    public function CustomFieldValues()
    {
        return $this->morphMany(CustomFieldValue::class, 'modelable');
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

     public function city()
    {
        return $this->belongsTo(City::class);
    }


}
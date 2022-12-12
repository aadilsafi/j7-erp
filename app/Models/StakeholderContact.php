<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class StakeholderContact extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'stakeholder_id',
        'country_id',
        'city_id',
        'state_id',
        'full_name',
        'father_name',
        'occupation',
        'designation',
        'cnic',
        'ntn',
        'contact',
        'address',
        'nationality',
        'countryDetails',
        'stakeholder_contact_id'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName(get_class($this))->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    public function stakeholder()
    {
        return $this->belongsTo(Stakeholder::class);
    }
}

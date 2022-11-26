<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class CustomField extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'site_id',
        'name',
        'slug',
        'type',
        'values',
        'disabled',
        'required',
        'in_table',
        'multiple',
        'min',
        'max',
        'minlength',
        'maxlength',
        'bootstrap_column',
        'order',
        'custom_field_model',
    ];

    protected $casts = [
        'site_id' => 'integer',
        'values' => 'array',
        'disabled' => 'boolean',
        'required' => 'boolean',
        'in_table' => 'boolean',
        'multiple' => 'boolean',
        'min' => 'integer',
        'max' => 'integer',
        'minlength' => 'integer',
        'maxlength' => 'integer',
        'bootstrap_column' => 'integer',
        'order' => 'integer',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName(get_class($this))->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function CustomFieldValue()
    {
        return $this->hasMany(CustomFieldValue::class);
    }

    public function modelable()
    {
        return $this->morphTo();
    }
}

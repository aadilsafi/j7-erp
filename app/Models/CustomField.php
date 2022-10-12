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
        'name',
        'type',
        'values',
        'disabled',
        'required',
        'in_table',
        'readonly',
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
        'values' => 'array',
        'disabled' => 'boolean',
        'required' => 'boolean',
        'in_table' => 'boolean',
        'readonly' => 'boolean',
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
}

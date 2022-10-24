<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Type extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'site_id',
        'name',
        'parent_id',
        'slug',
        'status',
    ];

    protected $cast = [
        'site_id'=> 'integer',
        'name' => 'string',
        'parent_id' => 'integer',
        'slug' => 'string',
        'status' => 'boolean',
    ];

    public $rules = [
        'type' => 'required|numeric',
        'type_name' => 'required|string|min:1|max:255',
        // 'type_slug' => 'required|string|min:1|max:255|unique:types,slug',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName(get_class($this))->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }
}

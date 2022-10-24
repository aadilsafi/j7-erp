<?php

namespace App\Models;

use Exception;
use App\Models\AccountHead;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Type extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'site_id',
        'name',
        'parent_id',
        'slug',
        'status',
        'account_added',
        'account_number',
    ];

    protected $cast = [
        'site_id'=> 'integer',
        'name' => 'string',
        'parent_id' => 'integer',
        'slug' => 'string',
        'status' => 'boolean',
        'account_added' => 'boolean',
        'account_number' => 'string',
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

    public function modelable()
    {
        return $this->morphOne(AccountHead::class,'modelable');
    }

}

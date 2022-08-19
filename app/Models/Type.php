<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Type extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'site_id',
        'name',
        'parent_id',
        'slug',
    ];

    public $requestRules = [
        'type' => 'required|numeric',
        'type_name' => 'required|string|min:1|max:255',
        // 'type_slug' => 'required|string|min:1|max:255|unique:types,slug',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }
}

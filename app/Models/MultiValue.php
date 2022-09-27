<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MultiValue extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'value',
        'type',
    ];

    protected $casts = [
        'value' => 'string',
        'type' => 'string',
    ];

    public function multivalueable()
    {
        return $this->morphTo();
    }
}

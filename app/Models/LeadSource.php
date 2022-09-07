<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeadSource extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'site_id',
        'name',
    ];

    protected $cast = [
        'site_id' => 'integer',
        'name' => 'string',
    ];

    public $rules = [
        'lead_source_name' => 'required|string',
    ];
}

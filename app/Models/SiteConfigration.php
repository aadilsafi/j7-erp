<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteConfigration extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $requestRules = [
        'site_id' => 'required',
        'site_max_floors' => 'required',
        'floor_prefix' => 'required',
        'unit_number_digits' => 'required',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }
}

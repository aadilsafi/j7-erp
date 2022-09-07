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
        'site_max_floors' => 'required|numeric|min:0',
        'site_token_percentage' => 'required|numeric|min:0|max:100',
        'site_down_payment_percentage' => 'required|numeric|min:0|max:100',
        'floor_prefix' => 'required|string|max:5',
        'unit_number_digits' => 'required|numeric|in:2,3',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }
}

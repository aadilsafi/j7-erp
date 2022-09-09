<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteConfigration extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $rules = [
        'name' => 'sometimes|between:1,255',
        'address' => 'sometimes|between:1,255',
        'area_width' => 'sometimes|numeric|gt:0',
        'area_length' => 'sometimes|numeric|gt:0',
        'selected_tab' => 'required|in:site,floor,unit',
        'arr_site' => 'sometimes|array',
        'arr_floor' => 'sometimes|array',
        'arr_unit' => 'sometimes|array',

        'arr_site.site_max_floors' => 'sometimes|numeric|min:0',
        'arr_site.site_token_percentage' => 'sometimes|numeric|gt:0|max:100',
        'arr_site.site_down_payment_percentage' => 'sometimes|numeric|gt:0|max:100',

        'arr_floor.floor_prefix' => 'sometimes|string|alpha_num|max:5',

        'arr_unit.unit_number_digits' => 'sometimes|numeric|in:2,3',
    ];

    public $ruleMessages = [
        'arr_site.site_max_floors.numeric' => 'The site max floors must be a number.',
        'arr_site.site_max_floors.min' => 'The site max floors must be at least 0.',
        'arr_site.site_token_percentage.numeric' => 'The site token percentage must be a number.',
        'arr_site.site_token_percentage.gt' => 'The site token percentage must be greater than 0.',
        'arr_site.site_token_percentage.max' => 'The site token percentage may not be greater than 100.',
        'arr_site.site_down_payment_percentage.numeric' => 'The site down payment percentage must be a number.',
        'arr_site.site_down_payment_percentage.gt' => 'The site down payment percentage must be greater than 0.',
        'arr_site.site_down_payment_percentage.max' => 'The site down payment percentage may not be greater than 100.',

        'arr_floor.floor_prefix.string' => 'The floor prefix must be a string.',
        'arr_floor.floor_prefix.alpha_num' => 'The floor prefix must only contain letters and numbers.',
        'arr_floor.floor_prefix.max' => 'The floor prefix may not be greater than 5 characters.',

        'arr_unit.unit_number_digits.numeric' => 'The unit number digits must be a number.',
        'arr_unit.unit_number_digits.in' => 'The selected unit number digits is invalid.',
    ];
    public function site()
    {
        return $this->belongsTo(Site::class);
    }
}

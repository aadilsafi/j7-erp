<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_id',
        'name',
        'parent_id',
        'has_team'
    ];

    public $rules = [
        'team' => 'required|numeric',
        'team_name' => 'required|string|min:1|max:255',
        'has_team' => 'boolean|in:0,1',
        'user_id' => 'required_if:has_team,0',
    ];


    public function users()
    {
        return $this->belongsToMany(User::class, 'team_users')->withPivot('site_id');
    }
}

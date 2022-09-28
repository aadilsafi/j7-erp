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
        
    ];

    public $rules = [
        'team' => 'required|numeric',
        'team_name' => 'required|string|min:1|max:255',
        'user_id' => 'required'
    ];


    public function users()
    {
        return $this->belongsToMany(User::class,'team_users');
    }
}

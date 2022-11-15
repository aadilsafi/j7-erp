<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempKins extends Model
{
    use HasFactory;

    protected $fillable= [
        'relation' ,
        'stakeholder_cnic' ,
        'kin_cnic',
    ];
}

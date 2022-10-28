<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempStakeholder extends Model
{
    use HasFactory;
    protected $fillable = [
        'full_name',
        'father_name',
        'occupation',
        'designation',
        'cnic',
        'ntn',
        'contact',
        'address',
        'comments',
        'is_dealer',
        'is_vendor',
        'is_customer',
        'is_kin',
        'parent_cnic',
        'relation'
    ];
}

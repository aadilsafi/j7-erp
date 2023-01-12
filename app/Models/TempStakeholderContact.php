<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempStakeholderContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'stakeholder_cnic',
        'full_name',
        'father_name',
        'cnic',
        'contact_no',
        'designation',
        'occupation',
        'ntn',
        'address',
    ];
}

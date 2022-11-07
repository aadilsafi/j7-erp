<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempBank extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'account_number',
        'branch_code',
        'address',
        'contact_number',
        'comments'
    ];
}

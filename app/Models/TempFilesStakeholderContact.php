<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempFilesStakeholderContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_doc_no',
        'contact_cnic',
        'kin_cnic',
    ];
}

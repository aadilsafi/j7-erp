<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileStakeholderContact extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'file_management_id',
        'stakeholder_contact_id',
        'site_id'
     ];
}

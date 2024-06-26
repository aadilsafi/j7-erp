<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempFiles extends Model
{
    use HasFactory;

    protected $fillable = [
        'doc_no',
        'sales_plan_doc_no',
        'registration_no',
        'application_no',
        'note_serial_number',
        'deal_type',
        'created_date',
        'image_url',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempFiles extends Model
{
    use HasFactory;

    protected $fillable = [
        'doc_no',
        'unit_short_label',
        'stakeholder_cnic',
        'total_price',
        'down_payment_total',
        'sales_plan_approval_date',
        'registration_no',
        'application_no',
        'note_serial_number',
        'deal_type',
        'created_date',
        'image_url',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempJournalVoucherEntries extends Model
{
    use HasFactory;

    protected $fillable = [
        'doc_no',
        'jv_remarks',
        'created_date',
        'user_email',
        'status',
        'checked_by',
        'checked_date',
        'posted_by',
        'posted_date',
        'account_code',
        'credit',
        'debit',
        'remarks',
        'date',
        
    ];
}

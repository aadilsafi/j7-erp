<?php

namespace App\Models;

use App\Models\Site;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Receipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_id',
        'unit_id',
        'sales_plan_id',
        'name',
        'cnic',
        'mode_of_payment',
        'cheque_no',
        'online_instrument_no',
        'drawn_on_bank',
        'transaction_date',
        'amount_in_words',
        'amount_in_numbers',
        'purpose',
        'installment_number',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

}

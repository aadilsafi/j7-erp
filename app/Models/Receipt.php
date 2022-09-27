<?php

namespace App\Models;

use App\Models\Site;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Receipt extends Model implements HasMedia
{
    use HasFactory , InteractsWithMedia;

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
        'phone_no',
        'other_value',
        'pay_order',
        'amount_received',
        'attachment',
        'comments',
        'status',
        'bank_details',
    ];

    public $rules = [
        'receipts.item_idunit_id' => 'required',
        'receipts.mode_of_payment' => 'required',
        'receipts.amount_in_numbers' => 'required',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function salesPlan()
    {
        return $this->belongsTo(SalesPlan::class);
    }
}

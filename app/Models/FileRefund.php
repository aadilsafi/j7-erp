<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileRefund extends Model
{
    use HasFactory;


    protected $fillable = [
        'site_id',
        'file_id',
        'unit_id',
        'stakeholder_id',
        'dealer_id',
        'stakeholder_data',
        'unit_data',
        'dealer_data',
        'amount_to_be_refunded',
        'payment_due_date',
        'amount_remarks',
        'status',
        'comments',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function file()
    {
        return $this->belongsTo(FileManagement::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function stakeholder()
    {
        return $this->belongsTo(Stakeholder::class);
    }

    public function fileRefundAttachments()
    {
        return $this->hasMany(FileRefundAttachment::class);
    }

}

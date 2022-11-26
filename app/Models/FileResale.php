<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class FileResale extends Model
{
    use HasFactory, LogsActivity;


    protected $fillable = [
        'site_id',
        'file_id',
        'unit_id',
        'sales_plan_id',
        'stakeholder_id',
        'buyer_id',
        'buyer_data',
        'amount_profit',
        'rebate_amount',
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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName(get_class($this))->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

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

    public function fileResaleAttachments()
    {
        return $this->hasMany(FileResaleAttachment::class);
    }

    public function CustomFieldValues()
    {
        return $this->morphMany(CustomFieldValue::class, 'modelable');
    }
}

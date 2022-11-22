<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class FileTitleTransfer extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'site_id',
        'file_id',
        'unit_id',
        'sales_plan_id',
        'stakeholder_id',
        'transfer_person_id',
        'transfer_person_data',
        'transfer_rate',
        'stakeholder_data',
        'unit_data',
        'amount_to_be_paid',
        'payment_due_date',
        'amount_remarks',
        'status',
        'comments',
        'kin_data',
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

    public function fileTitleTransferAttachments()
    {
        return $this->hasMany(FileTitleTransferAttachment::class);
    }
}

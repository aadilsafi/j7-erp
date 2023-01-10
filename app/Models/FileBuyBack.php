<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class FileBuyBack extends Model
{
    use HasFactory, LogsActivity,SoftDeletes;

    protected $fillable = [
        'site_id',
        'doc_no',
        'file_id',
        'sales_plan_id',
        'unit_id',
        'stakeholder_id',
        'dealer_id',
        'stakeholder_data',
        'unit_data',
        'dealer_data',
        'amount_to_be_refunded',
        'amount_profit',
        'payment_due_date',
        'amount_remarks',
        'status',
        'comments',
        'serial_no',
        'user_id',
        'checked_date',
        'checked_by',
        'approved_by',
        'approved_date',
        'reverted_by',
        'reverted_date',
        'cheque_active_by',
        'cheque_active_date',
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

    public function FileBuyBackLabelsAttachment()
    {
        return $this->hasMany(FileBuyBackLabelsAttachment::class);
    }
    public function CustomFieldValues()
    {
        return $this->morphMany(CustomFieldValue::class, 'modelable');
    }
}

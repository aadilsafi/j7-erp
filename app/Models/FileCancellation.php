<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use App\Models\FileCancellationAttachment;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class FileCancellation extends Model
{
    use HasFactory, LogsActivity,SoftDeletes;

    protected $fillable = [
        'site_id',
        'doc_no',
        'file_id',
        'unit_id',
        'sales_plan_id',
        'stakeholder_id',
        'dealer_id',
        'stakeholder_data',
        'unit_data',
        'dealer_data',
        'amount_to_be_refunded',
        'cancellation_charges',
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

    public function FileCancelLabelsAttachment()
    {
        return $this->hasMany(FileCancellationAttachment::class);
    }

    public function templates(){
        return Template::select('templates.*')->join('model_templates','model_templates.template_id' , '=', 'templates.id')->where('model_type', get_class($this))->get();
        // return $this->belongsToMany(Template::class,'model_templates','model_type')->wherePivot('model_type','=',get_class($this));
    }

    public function CustomFieldValues()
    {
        return $this->morphMany(CustomFieldValue::class, 'modelable');
    }
}

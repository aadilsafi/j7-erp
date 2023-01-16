<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class StakeholderInvestor extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName(get_class($this))->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    protected $fillable = [
        'site_id',
        'investor_id',
        'user_id',
        'serial_number',
        'doc_no',
        'total_received_amount',
        'total_payable_amount',
        'created_date',
        'checked_by',
        'checked_date',
        'approved_by',
        'approved_date',
        'dis_approved_by',
        'dis_approved_date',
        'reverted_by',
        'reverted_date',
        'jve_number',
        'status',
        'deal_status',
        'remarks',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function investor()
    {
        return $this->belongsTo(Stakeholder::class,'investor_id','id');
    }
}

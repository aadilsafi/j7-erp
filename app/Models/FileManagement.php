<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;

class FileManagement extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, SoftDeletes;

    protected $fillable = [
        'site_id',
        'unit_id',
        'unit_data',
        'stakeholder_id',
        'stakeholder_data',
        'registration_no',
        'application_no',
        'status',
        'deal_type',
        'comments',
        'file_action_id',
    ];

    public $rules = [
        'application_form.*.registration_no' => 'required',
        'application_form.*.application_no' => 'required',
        'application_form.*.photo' => 'sometimes|max:1',
    ];

    public $ruleMessages = [
        "application_form.*.registration_no.required" => "Registration No. is required.",
        "application_form.*.application_no" => "Application No. is required.",
        "application_form.*.photo" => "Maximum 1 attachments are required.",
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class)->with('type','status');
    }

    public function stakeholder()
    {
        return $this->belongsTo(Stakeholder::class);
    }

    public function fileAction()
    {
        return $this->belongsTo(FileAction::class,'file_action_id');
    }

    public function fileRefund()
    {
        return $this->hasMany(FileRefund::class,'file_id');
    }

    public function fileBuyBack()
    {
        return $this->hasMany(FileBuyBack::class,'file_id');
    }
}
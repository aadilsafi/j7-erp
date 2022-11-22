<?php

namespace App\Models;

use App\Models\StakeholderType;
use App\Utils\Enums\StakeholderTypeEnum;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Stakeholder extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia, LogsActivity;

    protected $fillable = [
        'site_id',
        'country_id',
        'city_id',
        'state_id',
        'full_name',
        'father_name',
        'occupation',
        'designation',
        'cnic',
        'is_imported',
        'ntn',
        'contact',
        'address',
        'parent_id',
        'comments',
        'relation',
        'optional_contact_number',
        'nationality',
    ];

    public $rules = [
        // 'site_id' => 'required|numeric',
        'full_name' => 'required|string|min:1|max:50',
        'father_name' => 'required|string|min:1|max:50',
        'occupation' => 'required|string|min:1|max:50',
        'designation' => 'required|string|min:1|max:50',
        'cnic' => 'required|unique:stakeholders,cnic',
        // 'cnic' => 'required|numeric|digits:13|unique:stakeholders,cnic',
        'ntn' => 'required|numeric',
        'contact' => 'required|string|min:1|max:20',
        'address' => 'required|string',
        'parent_id' => 'nullable|numeric',
        'comments' => 'nullable|string',
        'relation' => 'nullable|string|min:1|max:50',
        'attachment' => 'sometimes|min:2',
        'stakeholder_type' => 'required|in:C,V,D,L,K',
        'contact-persons' => 'nullable|array',
        'next-of-kins' => 'nullable|array',
        'city_id' => 'nullable|numeric',
        'state_id' => 'nullable|numeric',
        'country_id' => 'nullable|numeric',
        // 'contact-persons.*.cnic' => 'nullable|numeric|digits_between:1,15',
    ];

    public $ruleMessages = [
        'attachment.min' => 'Minimum 2 attachments are required.',
        'contact-persons.*.cnic.numeric' => 'CNIC must be numeric.',
        'contact-persons.*.cnic.min' => 'CNIC must be at least 1 digit.',
        'contact-persons.*.cnic.max' => 'CNIC may not be greater than 15 digits.',
    ];

    protected $casts = [
        'site_id' => 'integer',
        'full_name' => 'string',
        'father_name' => 'string',
        'occupation' => 'string',
        'designation' => 'string',
        'cnic' => 'string',
        'ntn' => 'string',
        'contact' => 'string',
        'address' => 'string',
        'parent_id' => 'integer',
        'relation' => 'string',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName(get_class($this))->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function stakeholder_types()
    {
        return $this->hasMany(StakeholderType::class);
    }

    public function multiValues()
    {
        return $this->morphMany(MultiValue::class, 'multivalueable');
    }

    public function nextOfKin()
    {
        return $this->hasMany(StakeholderNextOfKin::class);
    }

    public function contacts()
    {
        return $this->hasMany(StakeholderContact::class);
    }

    public function dealer_stakeholder()
    {
        return $this->hasMany(StakeholderType::class)->where('type', 'D')->where('status', 1);
    }

    public function stakeholderAsCustomer()
    {
        return $this->hasMany(StakeholderType::class)->where('type', 'C');
    }
    public function salesPlans()
    {
        return $this->hasMany(SalesPlan::class);
    }
}
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
        'stakeholder_as',
        'full_name',
        'father_name',
        'cnic',
        'ntn',
        'contact',
        'date_of_birth',
        'is_filer',
        'is_local',
        'designation',
        'occupation',
        'industry',
        'nationality',
        'email',
        'office_email',
        'mobile_contact',
        'mobileContactCountryDetails',
        'office_contact',
        'OfficeConatctCountryDetails',
        'residential_address',
        'residential_address_type',
        'residential_country_id',
        'residential_state_id',
        'residential_city_id',
        'residential_postal_code',
        'mailing_address',
        'mailing_address_type',
        'mailing_country_id',
        'mailing_state_id',
        'mailing_city_id',
        'mailing_postal_code',
        'referred_by',
        'source',
        'comments',
        'parent_id',
        'parent_company',
        'website',
        'relation',
    ];

    public $rules = [
        'stakeholder_as' => 'required|in:i,c',
        // as individual validations
        'full_name' => 'exclude_if:stakeholder_as,c|required|string|min:1|max:50',
        'father_name' => 'exclude_if:stakeholder_as,c|required|string|min:1|max:50',
        'occupation' => 'exclude_if:stakeholder_as,c|required|string|max:50',
        'designation' => 'exclude_if:stakeholder_as,c|nullable|string|max:50',
        'cnic' => 'exclude_if:stakeholder_as,c|unique:stakeholders,cnic',
        'dob' => 'exclude_if:stakeholder_as,c|required|date|before:today',
        'is_filer' => 'exclude_if:stakeholder_as,c|boolean',
        'ntn' => 'exclude_if:stakeholder_as,c|required_if:is_file,1|unique:stakeholders,ntn',
        'nationality' => 'exclude_if:stakeholder_as,c|required|string|max:50',
        'individual_email' => 'exclude_if:stakeholder_as,c|email|unique:stakeholders,email',
        'office_email' => 'exclude_if:stakeholder_as,c|nullable|email|unique:stakeholders,office_email',
        'mobile_contact' => 'exclude_if:stakeholder_as,c|required|string|min:1|max:20',
        'office_contact' => 'exclude_if:stakeholder_as,c|nullable|string|min:1|max:20',
        'source' => 'exclude_if:stakeholder_as,c|sometimes',
        'referred_by' => 'exclude_if:stakeholder_as,c|sometimes',

        // as company validations
        'company_name' => 'exclude_if:stakeholder_as,i|string|min:1|max:50',
        'registration' => 'exclude_if:stakeholder_as,i|unique:stakeholders,cnic',
        'strn' => 'exclude_if:stakeholder_as,i|required|unique:stakeholders,ntn',
        'website' => 'exclude_if:stakeholder_as,i|nullable|string',
        'industry' => 'exclude_if:stakeholder_as,i|nullable|string|min:1|max:50',
        'parent_company' => 'exclude_if:stakeholder_as,i|nullable|string|min:1|max:50',
        'office_contact' => 'exclude_if:stakeholder_as,i|nullable|string|min:1|max:20',
        'office_email' => 'exclude_if:stakeholder_as,i|nullable|email|unique:stakeholders,office_email',

        // common validations
        'comments' => 'nullable|string',
        'attachment' => 'sometimes|min:2',
        'stakeholder_type' => 'required|in:C,V,D,L,K',
        'contact-persons' => 'nullable|array',
        'next-of-kins' => 'nullable|array',

        'residential_address_type' => 'required|string',
        'residential_country' => 'required|numeric',
        'residential_state' => 'required|numeric',
        'residential_city' => 'required|numeric',
        'residential_postal_code' => 'required|numeric',
        'residential_address' => 'required|string',

        'mailing_address_type' => 'required|string',
        'mailing_country' => 'required|numeric',
        'mailing_state' => 'required|numeric',
        'mailing_city' => 'required|numeric',
        'mailing_postal_code' => 'required|numeric',
        'mailing_address' => 'required|string',

        'city_id' => 'nullable|numeric',
        'state_id' => 'nullable|numeric',
        'country_id' => 'nullable|numeric',
        'next-of-kins.*.relation' => 'required_if:stakeholder_type,C',
        'next-of-kins.*.relation' => 'required_if:stakeholder_type,C',

    ];

    public $ruleMessages = [
        'attachment.min' => 'Minimum 2 attachments are required.',
        'contact-persons.*.cnic.numeric' => 'CNIC must be numeric.',
        'contact-persons.*.cnic.min' => 'CNIC must be at least 1 digit.',
        'contact-persons.*.cnic.max' => 'CNIC may not be greater than 15 digits.',
        'next-of-kins.*.relation' => 'Kin Relation Field is Required.',
        'cnic.exists' => 'Cnic is Blacklisted.',
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

    public function CustomFieldValues()
    {
        return $this->morphMany(CustomFieldValue::class, 'modelable');
    }

    public function residentialCountry()
    {
        return $this->belongsTo(Country::class, 'residential_country_id');
    }

    public function residentialState()
    {
        return $this->belongsTo(State::class, 'residential_state_id');
    }

    public function residentialCity()
    {
        return $this->belongsTo(City::class, 'residential_city_id');
    }

    public function mailingCountry()
    {
        return $this->belongsTo(Country::class, 'mailing_country_id');
    }

    public function mailingState()
    {
        return $this->belongsTo(State::class, 'mailing_state_id');
    }

    public function mailingCity()
    {
        return $this->belongsTo(City::class, 'mailing_city_id');
    }
}

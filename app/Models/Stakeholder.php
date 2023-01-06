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
        'OfficeContactCountryDetails',
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
        'origin',
        'strn',
        'passport_no',
        'crm_id',
        'pin_code',
    ];

    public $rules = [
        'stakeholder_as' => 'required|bail|in:i,c',
        'stakeholder_type' => 'required|in:C,V,D,L,K',

        // as individual validations
        'individual.full_name' => 'exclude_if:stakeholder_as,c|required|string|min:1|max:50',
        'individual.father_name' => 'exclude_if:stakeholder_as,c|exclude_if:stakeholder_type,L|required|string|min:1|max:50',
        'individual.occupation' => 'exclude_if:stakeholder_as,c|exclude_if:stakeholder_type,L|required|string|max:50',
        'individual.cnic' => 'exclude_if:stakeholder_as,c|exclude_if:stakeholder_type,L|unique:stakeholders,cnic',
        'individual.passport_no' => 'exclude_if:stakeholder_as,c|exclude_if:stakeholder_type,L|sometimes|nullable|unique:stakeholders,passport_no',
        'individual.ntn' => 'exclude_if:stakeholder_as,c|exclude_if:stakeholder_type,L|sometimes|nullable|unique:stakeholders,ntn',
        'individual.individual_email' => 'exclude_if:stakeholder_as,c|exclude_if:stakeholder_type,L|nullable|sometimes|email|unique:stakeholders,email',
        'individual.office_email' => 'exclude_if:stakeholder_as,c|exclude_if:stakeholder_type,L|nullable|sometimes|email|unique:stakeholders,office_email',
        'individual.mobile_contact' => 'exclude_if:stakeholder_as,c|exclude_if:stakeholder_type,L|required|string|min:1|max:20|unique:stakeholders,mobile_contact',
        'individual.office_contact' => 'exclude_if:stakeholder_as,c|exclude_if:stakeholder_type,L|nullable|string|min:1|max:20|unique:stakeholders,office_contact',
        'individual.dob' => 'exclude_if:stakeholder_as,c|exclude_if:stakeholder_type,L|required|date|before:today',
        'individual.designation' => 'exclude_if:stakeholder_as,c|exclude_if:stakeholder_type,L|nullable|string|max:50',
        'individual.nationality' => 'exclude_if:stakeholder_as,c|exclude_if:stakeholder_type,L|required',
        'individual.source' => 'exclude_if:stakeholder_as,c|exclude_if:stakeholder_type,L|sometimes',
        'individual.referred_by' => 'exclude_if:stakeholder_as,c|exclude_if:stakeholder_type,L|sometimes',

        // as company validations
        'company.company_name' => 'exclude_if:stakeholder_as,i|exclude_if:stakeholder_type,L|string|min:1|max:50',
        'company.registration' => 'exclude_if:stakeholder_as,i|exclude_if:stakeholder_type,L|unique:stakeholders,cnic',
        'company.industry' => 'exclude_if:stakeholder_as,i|exclude_if:stakeholder_type,L|nullable|string|min:1|max:50',
        'company.origin' => 'exclude_if:stakeholder_as,i|exclude_if:stakeholder_type,L|nullable',
        'company.company_ntn' => 'exclude_if:stakeholder_as,i|exclude_if:stakeholder_type,L|required|unique:stakeholders,ntn',
        'company.strn' => 'exclude_if:stakeholder_as,i|sometimes|exclude_if:stakeholder_type,L|nullable|unique:stakeholders,strn',
        'company.office_contact' => 'exclude_if:stakeholder_as,i|exclude_if:stakeholder_type,L|nullable|string|min:1|max:20|unique:stakeholders,office_contact',
        'company.website' => 'exclude_if:stakeholder_as,i|exclude_if:stakeholder_type,L|nullable|string',
        'company.parent_company' => 'exclude_if:stakeholder_as,i|exclude_if:stakeholder_type,L|nullable|string|min:1|max:50',
        'company.office_email' => 'exclude_if:stakeholder_as,i|exclude_if:stakeholder_type,L|nullable|email|unique:stakeholders,office_email',

        // common validations
        'comments' => 'nullable|string',
        'attachment' => 'sometimes|min:2',
        'contact-persons' => 'nullable|array',
        'next-of-kins' => 'nullable|array',

        'residential.address_type' => 'exclude_if:stakeholder_type,L|required|string',
        'residential.country' => 'exclude_if:stakeholder_type,L|required|numeric',
        'residential.state' => 'exclude_if:stakeholder_type,L|required|numeric',
        'residential.city' => 'exclude_if:stakeholder_type,L|required|numeric',
        'residential.postal_code' => 'exclude_if:stakeholder_type,L|required|numeric',
        'residential.address' => 'exclude_if:stakeholder_type,L|required|string',

        'mailing.address_type' => 'exclude_if:stakeholder_type,L|required|string',
        'mailing.country' => 'exclude_if:stakeholder_type,L|required|numeric',
        'mailing.state' => 'exclude_if:stakeholder_type,L|required|numeric',
        'mailing.city' => 'exclude_if:stakeholder_type,L|required|numeric',
        'mailing.postal_code' => 'exclude_if:stakeholder_type,L|required|numeric',
        'mailing.address' => 'exclude_if:stakeholder_type,L|required|string',

        'next-of-kins.*.relation' => 'required_if:stakeholder_type,C',
        'next-of-kins.*.relation' => 'required_if:stakeholder_type,C',

    ];

    public $ruleMessages = [
        'attachment.min' => 'Minimum 2 attachments are required.',
        'contact-persons.*.cnic.numeric' => 'CNIC must be numeric.',
        'contact-persons.*.cnic.min' => 'CNIC must be at least 1 digit.',
        'contact-persons.*.cnic.max' => 'CNIC may not be greater than 15 digits.',
        'next-of-kins.*.relation' => 'Kin Relation Field is Required.',
        'individual.full_name.required' => 'Full Name is Required.',
        'individual.father_name.required' => 'Father Name is Required.',
        'individual.occupation.required' => 'Occupation is Required.',
        'individual.cnic.required' => 'Cnic is Required.',
        'individual.cnic.numeric' => 'Cnic must be numeric.',
        'individual.cnic.unique' => 'Cnic is already taken.',
        'individual.passport_no.unique' => 'Passport No is already taken.',
        'individual.ntn.unique' => 'NTN is already taken.',
        'individual.individual_email.unique' => 'Email is already taken.',
        'individual.office_email.unique' => 'Office Email is already taken.',

        'cnic.exists' => 'Cnic is Blacklisted.',
        'ntn.required_if' => 'NTN is required if filer is checked.',
        'dob.before' => 'Date of Birth must be a date before today.',
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

    public function KinStakeholders()
    {
        return $this->belongsToMany(Stakeholder::class, 'stakeholder_next_of_kin', 'kin_id')
            ->withPivot('site_id', 'relation')->withTimestamps()->orderByPivot('created_at', 'desc');
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

    public function nationalityCountry()
    {
        return $this->belongsTo(Country::class, 'nationality');
    }

    public function originCountry()
    {
        return $this->belongsTo(Country::class, 'origin');
    }

    public function leadSource()
    {
        return $this->belongsTo(LeadSource::class, 'source');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempCompanyStakeholder extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'registration',
        'industry',
        'strn',
        'ntn',
        'origin',
        'email',
        'office_email',
        'mobile_contact',
        'office_contact',
        'website',
        'parent_company',
        'residential_address',
        'residential_address_type',
        'residential_country',
        'residential_state',
        'residential_city',
        'residential_postal_code',
        'mailing_address',
        'mailing_address_type',
        'mailing_country',
        'mailing_state',
        'mailing_city',
        'mailing_postal_code',
        'comments',
        'is_dealer',
        'is_vendor',
        'is_customer',
        
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempStakeholder extends Model
{
    use HasFactory;
    protected $fillable = [
        'full_name',
        'father_name',
        'cnic',
        'passport_no',
        'ntn',
        'occupation',
        'designation',
        'is_local',
        'nationality',
        'date_of_birth',
        'email',
        'office_email',
        'mobile_contact',
        'office_contact',
        'referred_by',
        'source',
        'residential_address',
        'residential_address_type',
        'residential_country',
        'residential_state',
        'residential_city',
        'residential_postal_code',
        'same_address_for_mailing',
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
        'is_kin',
    ];
}

<div class="card mb-1" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
    <div class="card-body">
        <div class="row mb-1">
            <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                <label class="form-label" style="font-size: 15px" for="stakeholder_as">Stakeholder As
                    <span class="text-danger">*</span></label>
                <select class="form-select form-select-lg" id="stakeholder_as" name="stakeholder_as">
                    <option value="0" selected>Select Stakeholder As</option>
                    <option value="i" {{ old('stakeholder_as') == 'i' ? 'selected' : '' }}>Individual</option>
                    <option value="c" {{ old('stakeholder_as') == 'c' ? 'selected' : '' }}>Company</option>
                </select>
                @error('stakeholder_as')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="py-1" id="stakeholderType">
            @if (!isset($stakeholder))
                <div class="row mb-1">
                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                        <label class="form-label" style="font-size: 15px" for="stakeholder_type">Stakeholder Type <span
                                class="text-danger">*</span></label>
                        <select class="form-select form-select-lg select2" id="stakeholder_type" name="stakeholder_type"
                            {{ isset($stakeholder) ? 'disabled' : null }}>
                            <option value="0">Select Stakeholder Type</option>
                            @foreach ($stakeholderTypes as $key => $value)
                                <option value="{{ $value }}"
                                    {{ old('stakeholder_type') == $value ? 'selected' : '' }}>
                                    {{ Str::of($key)->lower()->ucfirst()->replace('_', ' ') }}
                                </option>
                            @endforeach
                        </select>
                        @error('stakeholder_type')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            @else
                <div class="row mb-1">
                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                        <div class="d-flex justify-content-between">
                            @forelse ($stakeholder->stakeholder_types as $type)
                                <div class="d-flex flex-column justify-content-center align-items-center">
                                    <span
                                        class="badge badge-light-{{ $type->status ? 'success' : 'danger' }} fs-5 mb-50">{{ $type->stakeholder_code }}</span>
                                    <div class="form-check form-switch form-check-success">
                                        <input type="checkbox" class="form-check-input"
                                            id="stakeholder_type_{{ $type->type }}"
                                            onchange="performAction('{{ $type->type }}')"
                                            name="stakeholder_type[{{ $type->type }}]" value="1"
                                            {{ $type->status ? 'checked' : null }}
                                            {{ $type->status || $type->type == 'K' ? 'disabled' : null }} />
                                        <label class="form-check-label" for="stakeholder_type_{{ $type->type }}">
                                            <span class="switch-icon-left"><i data-feather="check"></i></span>
                                            <span class="switch-icon-right"><i data-feather="x"></i></span>
                                        </label>
                                    </div>
                                </div>
                            @empty
                            @endforelse
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Company Form --}}

<div class="card" id="companyForm" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
    <div class="card-header">
        <h3><strong>Company informations: </strong></h3>
    </div>
    <div class="card-body">

        <div class="row mb-1">
            <div class="col-lg-6 col-md-6 position-relative">
                <label class="form-label fs-5" for="company_name">Company Name <span
                        class="text-danger">*</span></label>
                <input type="text" class="form-control form-control-md @error('full_name') is-invalid @enderror"
                    id="company_name" name="company_name" placeholder="Company Name"
                    value="{{ isset($stakeholder) ? $stakeholder->full_name : old('company_name') }}" />
                @error('full_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-lg-6 col-md-6 position-relative">
                <label class="form-label fs-5" for="registration">Registration # <span
                        class="text-danger">*</span></label>
                <input type="text"
                    class="cp_cnic form-control form-control-md @error('registration') is-invalid @enderror"
                    id="registration" name="registration" placeholder="Registration Number"
                    value="{{ isset($stakeholder) ? $stakeholder->cnic : old('registration') }}" />
                @error('registration')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row mb-1">
            <div class="col-lg-6 col-md-6 position-relative">
                <label class="form-label fs-5" for="industry">Industry <span class="text-danger">*</span></label>
                <input type="text" class="form-control form-control-md @error('industry') is-invalid @enderror"
                    id="industry" name="industry" placeholder="Industry"
                    value="{{ isset($stakeholder) ? $stakeholder->industry : old('industry') }}" />
                @error('industry')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-lg-6 col-md-6 position-relative">
                <label class="form-label fs-5" for="strn">Origin </label>
                <select class="select2" id="origin" name="origin">
                    <option value="0" selected>Select Company Origin</option>
                    @foreach ($country as $countryRow)
                        <option @if ((isset($stakeholder) && $stakeholder->origin) || old('origin') == $countryRow->id) selected @endif value="{{ $countryRow->id }}">
                            {{ $countryRow->name }}</option>
                    @endforeach
                </select>
                @error('origin')
                    <div class="invalid-feedback ">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row mb-1">
            <div class="col-lg-6 col-md-6 position-relative">
                <label class="form-label fs-5" for="company_ntn">NTN <span class="text-danger">*</span></label>
                <input type="text" class="form-control form-control-md @error('company_ntn') is-invalid @enderror"
                    id="company_ntn" name="company_ntn" placeholder="NTN"
                    value="{{ isset($stakeholder) ? $stakeholder->ntn : old('company_ntn') }}" />
                @error('company_ntn')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-lg-6 col-md-6 position-relative">
                <label class="form-label fs-5" for="strn">STRN <span class="text-danger">*</span></label>
                <input type="number" class="form-control form-control-md @error('strn') is-invalid @enderror"
                    id="strn" name="strn" placeholder="STRN"
                    value="{{ isset($stakeholder) ? $stakeholder->strn : old('strn') }}" />
                @error('strn')
                    <div class="invalid-feedback ">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row mb-1">
            <div class="col-lg-6 col-md-6 col-sm-6">
                <label class="form-label fs-5" for="company_office_contact">Office Contact <span
                        class="text-danger">*</span></label>
                <input type="tel"
                    class="form-control form-control-md ContactNoError optional_contact @error('company_office_contact') is-invalid @enderror"
                    id="company_office_contact" name="company_office_contact" placeholder="Office Contact"
                    value="{{ isset($stakeholder) ? $stakeholder->office_contact : old('company_office_contact') }}" />
                @error('company_office_contact')
                    <div class="invalid-feedback ">{{ $message }}</div>
                @enderror
            </div>

            <input type="hidden" name="CompanyOfficeContactCountryDetails" id="CompanyOfficeContactCountryDetails"
                value="{{ old('CompanyOfficeContactCountryDetails') }}">

            <div class="col-lg-6 col-md-6 col-sm-6">
                <label class="form-label fs-5" for="company_optional_contact">Optional Contact </label>
                <input type="tel"
                    class="form-control form-control-md OPTContactNoError contact @error('company_optional_contact') is-invalid @enderror"
                    id="company_optional_contact" name="company_optional_contact" placeholder="Optional Contact"
                    value="{{ isset($stakeholder) ? $stakeholder->mobile_contact : old('company_optional_contact') }}" />
                @error('company_optional_contact')
                    <div class="invalid-feedback ">{{ $message }}</div>
                @enderror
            </div>

            <input type="hidden" name="companyMobileContactCountryDetails" id="companyMobileContactCountryDetails"
                value="{{ old('companyMobileContactCountryDetails') }}">
        </div>

        <div class="row mb-1">
            <div class="col-lg-6 col-md-6 position-relative">
                <label class="form-label fs-5" for="company_email">Email</label>
                <input type="email"
                    class="form-control form-control-md @error('company_email') is-invalid @enderror"
                    id="company_email" name="company_email" placeholder="Email" autocomplete="false"
                    value="{{ isset($stakeholder) ? $stakeholder->email : old('company_email') }}" />
                @error('company_email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-lg-6 col-md-6 position-relative">
                <label class="form-label fs-5" for="company_office_email">Office Email</label>
                <input type="email" class="form-control form-control-md @error('office_email') is-invalid @enderror"
                    id="company_office_email" name="company_office_email" placeholder="Office Email"
                    autocomplete="false"
                    value="{{ isset($stakeholder) ? $stakeholder->office_email : old('company_office_email') }}" />
                @error('company_office_email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row mb-1">
            <div class="col-lg-6 col-md-6 position-relative">
                <label class="form-label fs-5" for="website">Website </label>
                <input type="url" class="form-control form-control-md @error('website') is-invalid @enderror"
                    id="website" name="website" placeholder="Website"
                    value="{{ isset($stakeholder) ? $stakeholder->website : old('website') }}" />
                @error('website')
                    <div class="invalid-feedback ">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-lg-6 col-md-6 position-relative">
                <label class="form-label fs-5" for="parent_company">Parent Company</label>
                <input type="text"
                    class="form-control form-control-md @error('parent_company') is-invalid @enderror"
                    id="parent_company" name="parent_company" placeholder="Parent Company Name"
                    value="{{ isset($stakeholder) ? $stakeholder->parent_company : old('parent_company') }}" />
                @error('parent_company')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

    </div>
</div>

{{-- Individual Form --}}
<div class="card" id="individualForm" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
    <div class="card-header">
        <h3><strong>Individual informations :</strong></h3>
    </div>
    <div class="card-body">

        <div class="row mb-1">
            <input type="hidden" value="0" name="parent_id">
            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                <label class="form-label fs-5" for="full_name">Full Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control form-control-md @error('full_name') is-invalid @enderror"
                    id="full_name" name="full_name" placeholder="Stakeholder Name"
                    value="{{ isset($stakeholder) ? $stakeholder->full_name : old('full_name') }}" />
                @error('full_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                <label class="form-label fs-5" for="father_name">Father / Husband Name <span
                        class="text-danger">*</span></label>
                <input type="text" class="form-control form-control-md @error('father_name') is-invalid @enderror"
                    id="father_name" name="father_name" placeholder="Father / Husband Name"
                    value="{{ isset($stakeholder) ? $stakeholder->father_name : old('father_name') }}" />
                @error('father_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                <label class="form-label fs-5" for="occupation">Occupation <span class="text-danger">*</span></label>
                <input type="text" class="form-control form-control-md @error('occupation') is-invalid @enderror"
                    id="occupation" name="occupation" placeholder="Occupation"
                    value="{{ isset($stakeholder) ? $stakeholder->occupation : old('occupation') }}" />
                @error('occupation')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="row mb-1">
            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                <label class="form-label fs-5" for="cnic">CNIC<span class="text-danger">*</span></label>
                <input type="text"
                    class="cp_cnic form-control form-control-md @error('cnic') is-invalid @enderror" id="cnic"
                    name="cnic" placeholder="CNIC Without Dashes"
                    value="{{ isset($stakeholder) ? $stakeholder->cnic : old('cnic') }}" />
                @error('cnic')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                <label class="form-label fs-5" for="passport_no">Passport No<span
                        class="text-danger">*</span></label>
                <input type="text"
                    class="cp_cnic form-control form-control-md @error('passport_no') is-invalid @enderror"
                    id="passport_no" name="passport_no" placeholder="Passport No Without Dashes"
                    value="{{ isset($stakeholder) ? $stakeholder->passport_no : old('passport_no') }}" />
                @error('passport_no')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                <label class="form-label fs-5" for="ntn">NTN </label>
                <input type="number" class="form-control form-control-md @error('ntn') is-invalid @enderror"
                    id="ntn" name="ntn" placeholder="NTN Number"
                    value="{{ isset($stakeholder) ? $stakeholder->ntn : old('ntn') }}" />
                @error('ntn')
                    <div class="invalid-feedback ">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="row mb-1">
            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                <label class="form-label fs-5" for="individual_email">Email </label>
                <input type="email"
                    class="form-control form-control-md @error('individual_email') is-invalid @enderror"
                    id="individual_email" name="individual_email" placeholder="Email" autocomplete="false"
                    value="{{ isset($stakeholder) ? $stakeholder->email : old('individual_email') }}" />
                @error('individual_email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                <label class="form-label fs-5" for="office_email">Office Email</label>
                <input type="email"
                    class="form-control form-control-md @error('office_email') is-invalid @enderror"
                    id="office_email" name="office_email" placeholder="Office Email" autocomplete="false"
                    value="{{ isset($stakeholder) ? $stakeholder->office_email : old('office_email') }}" />
                @error('office_email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                <label class="form-label fs-5" for="designation">Designation </label>
                <input type="text" class="form-control form-control-md @error('designation') is-invalid @enderror"
                    id="designation" name="designation" placeholder="Designation"
                    value="{{ isset($stakeholder) ? $stakeholder->designation : old('designation') }}" />
                @error('designation')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="row mb-1">
            <div class="col-lg-4 col-md-4 col-sm-4">
                <label class="form-label fs-5" for="mobile_contact">Mobile Contact <span
                        class="text-danger">*</span></label>
                <input type="tel"
                    class="form-control form-control-md ContactNoError contact @error('mobile_contact') is-invalid @enderror"
                    id="mobile_contact" name="mobile_contact" placeholder="Mobile Contact"
                    value="{{ isset($stakeholder) ? $stakeholder->mobile_contact : old('mobile_contact') }}" />
                @error('mobile_contact')
                    <div class="invalid-feedback ">{{ $message }}</div>
                @enderror
                <input type="hidden" name="mobileContactCountryDetails" id="mobileContactCountryDetails"
                    value="{{ old('mobileContactCountryDetails') }}">
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4">
                <label class="form-label fs-5" for="office_contact">Office Contact</label>
                <input type="tel"
                    class="form-control form-control-md OPTContactNoError optional_contact @error('office_contact') is-invalid @enderror"
                    id="office_contact" name="office_contact" placeholder="Office Contact"
                    value="{{ isset($stakeholder) ? $stakeholder->office_contact : old('office_contact') }}" />
                @error('office_contact')
                    <div class="invalid-feedback ">{{ $message }}</div>
                @enderror
            </div>
            <input type="hidden" name="OfficeContactCountryDetails" id="OfficeContactCountryDetails"
                value="{{ old('OfficeContactCountryDetails') }}">
            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                <label class="form-label fs-5" for="dob">Date of Birth <span
                        class="text-danger">*</span></label>
                <input id="dob" type="date" required placeholder="YYYY-MM-DD" name="dob"
                    class="form-control form-control-md" />
                @error('dob')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="row mb-1">
            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                <label class="form-label fs-5" for="referred_by">Refered By </label>
                <input type="text" class="form-control form-control-md @error('referred_by') is-invalid @enderror"
                    id="referred_by" name="referred_by" placeholder="Refered By" autocomplete="false"
                    value="{{ isset($stakeholder) ? $stakeholder->email : old('referred_by') }}" />
                @error('referred_by')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                <label class="form-label" style="font-size: 15px" for="source">Lead
                    Source</label>
                <select class="form-select form-select-md select2" id="source" name="source">
                    <option value="0">Select Lead Source</option>
                    @forelse ($leadSources as $leadSource)
                        <option value="{{ $leadSource->id }}"
                            {{ isset($stakeholder) && $stakeholder->source == $leadSource->id ? 'selected' : '' }}>
                            {{ $leadSource->name }}</option>
                    @empty
                    @endforelse
                </select>
            </div>
            <div class="col-1 col-md-1 col-lg-1 position-relative">
                <div class="d-flex flex-column">
                    <label class="form-check-label mb-1" for="is_local"> Local<span
                            class="text-danger">*</span></label>
                    <div class="form-check form-switch form-check-primary">
                        <input type="hidden" name="is_local" value="0" />
                        <input type="checkbox" class="form-check-input" id="is_local" name="is_local"
                            value="1"
                            {{ isset($stakeholder)
                                ? ($stakeholder->is_local == 1
                                    ? 'checked'
                                    : 'unchecked')
                                : (is_null(old('is_local'))
                                    ? 'checked'
                                    : (old('is_local') == 1
                                        ? 'checked'
                                        : 'unchecked')) }} />
                        <label class="form-check-label" for="is_local">
                            <span class="switch-icon-left"><i data-feather="check"></i></span>
                            <span class="switch-icon-right"><i data-feather="x"></i></span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                <label class="form-label fs-5" for="nationality">Nationality <span
                        class="text-danger">*</span></label>
                <select class="select2" id="nationality" name="nationality">
                    <option value="0" selected>Select Nationality</option>
                    @foreach ($country as $countryRow)
                        <option @if ((isset($stakeholder) && $stakeholder->nationality) || old('nationality') == $countryRow->id) selected @endif value="{{ $countryRow->id }}">
                            {{ $countryRow->name }}</option>
                    @endforeach
                </select>
                @error('nationality')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
</div>

{{-- Address fields --}}
<div class="card" id="common_form" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
    <div class="card-header">
        <h3><strong>Address </strong></h3>
    </div>
    <div class="card-body">

        <div class="row mb-1">
            <div class="col">
                <h4 class="mb-3" id="change_residential_txt"><u>Residential Address</u></h4>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative mb-1">
                        <label class="form-label" style="font-size: 15px" for="residential_address_type">Address
                            Type <span class="text-danger">*</span></label>
                        <input type="text"
                            class="form-control form-control-md @error('occupation') is-invalid @enderror"
                            id="residential_address_type" name="residential_address_type" placeholder="Address Type"
                            value="{{ isset($stakeholder) ? $stakeholder->residential_address_type : old('residential_address_type') }}" />
                        @error('residential_address_type')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative mb-1">
                        <label class="form-label" style="font-size: 15px" for="residential_country">Select
                            Country <span class="text-danger">*</span></label>
                        <select class="select2" id="residential_country" name="residential_country">
                            <option value="0" selected>Select Country</option>
                            @foreach ($country as $countryRow)
                                <option @if ((isset($stakeholder) && $stakeholder->residential_country_id) ||
                                    old('residential_country') == $countryRow->id) selected @endif
                                    value="{{ $countryRow->id }}">
                                    {{ $countryRow->name }}</option>
                            @endforeach
                        </select>
                        @error('residential_country')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative mb-1">
                        <label class="form-label" style="font-size: 15px" for="residential_state">Select
                            State <span class="text-danger">*</span></label>
                        <select class="select2" id="residential_state" name="residential_state">
                            <option value="0" selected>Select State</option>

                        </select>
                        @error('residential_state')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative mb-1">
                        <label class="form-label" style="font-size: 15px" for="residential_city">Select City <span
                                class="text-danger">*</span></label>
                        <select class="select2" id="residential_city" name="residential_city">
                            <option value="0" selected>Select City</option>
                        </select>
                        @error('residential_city')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative mb-1">
                        <label class="form-label" style="font-size: 15px" for="residential_postal_code">Postal Code
                            <span class="text-danger">*</span></label>
                        <input type="number"
                            class="form-control form-control-md @error('residential_postal_code') is-invalid @enderror"
                            id="residential_postal_code" name="residential_postal_code" placeholder="Postal Code"
                            value="{{ isset($stakeholder) ? $stakeholder->residential_postal_code : old('residential_postal_code') }}" />
                        @error('residential_postal_code')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                        <label class="form-label fs-5" for="residential_address">Residential Address <span
                                class="text-danger">*</span></label>
                        <textarea class="form-control @error('residential_address') is-invalid @enderror" name="residential_address"
                            id="residential_address" rows="3" placeholder="Address">{{ isset($stakeholder) ? $stakeholder->residential_address : old('residential_address') }}</textarea>
                        @error('residential_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="col">
                <h4 class="mb-1" id="change_mailing_txt"><u>Mailing Address</u></h4>
                <span class="text-info">( Same as Residential Address <input type="checkbox" id="cpyAddress" />
                    )</span>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative mb-1">
                        <label class="form-label" style="font-size: 15px" for="mailing_address_type">Address
                            Type <span class="text-danger">*</span></label>
                        <input type="text"
                            class="form-control form-control-md @error('occupation') is-invalid @enderror"
                            id="mailing_address_type" name="mailing_address_type" placeholder="Mailing Address Type"
                            value="{{ isset($stakeholder) ? $stakeholder->mailing_address_type : old('mailing_address_type') }}" />
                        @error('mailing_address_type')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative mb-1">
                        <label class="form-label" style="font-size: 15px" for="mailing_country">Select
                            Country <span class="text-danger">*</span></label>
                        <select class="select2" id="mailing_country" name="mailing_country">
                            <option value="0" selected>Select Country</option>
                            @foreach ($country as $countryRow)
                                <option @if ((isset($stakeholder) && $stakeholder->mailing_country_id) || old('country_id') == $countryRow->id) selected @endif
                                    value="{{ $countryRow->id }}">
                                    {{ $countryRow->name }}</option>
                            @endforeach
                        </select>
                        @error('mailing_country')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative mb-1">
                        <label class="form-label" style="font-size: 15px" for="mailing_state">Select State <span
                                class="text-danger">*</span></label>
                        <select class="select2" id="mailing_state" name="mailing_state">
                            <option value="0" selected>Select State</option>
                        </select>
                        @error('mailing_state')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative mb-1">
                        <label class="form-label" style="font-size: 15px" for="mailing_city">Select City <span
                                class="text-danger">*</span></label>
                        <select class="select2" id="mailing_city" name="mailing_city">
                            <option value="0" selected>Select City</option>
                        </select>
                        @error('mailing_city')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative mb-1">
                        <label class="form-label" style="font-size: 15px" for="mailing_postal_code">Postal Code
                            <span class="text-danger">*</span></label>
                        <input type="number"
                            class="form-control form-control-md @error('mailing_postal_code') is-invalid @enderror"
                            id="mailing_postal_code" name="mailing_postal_code" placeholder="mailing Postal Code"
                            value="{{ isset($stakeholder) ? $stakeholder->mailing_postal_code : old('mailing_postal_code') }}" />
                        @error('mailing_postal_code')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                        <label class="form-label fs-5" for="mailing_address">Mailing Address <span
                                class="text-danger">*</span></label>
                        <textarea class="form-control @error('mailing_address') is-invalid @enderror" name="mailing_address"
                            id="mailing_address" rows="3" placeholder="Stakeholder Address">{{ isset($stakeholder) ? $stakeholder->mailing_address : old('mailing_address') }}</textarea>
                        @error('mailing_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-1">
            <div class="col-lg col-md col-sm position-relative">
                <label class="form-label fs-5" for="comments">Comments</label>
                <textarea class="form-control @error('comments') is-invalid @enderror" name="comments" id="comments"
                    rows="3" placeholder="Comments">{{ isset($stakeholder) ? $stakeholder->comments : old('comments') }}</textarea>
                @error('comments')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
</div>

@if (isset($customFields) && count($customFields) > 0)

    <div class="card" id="custom_fields"
        style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
        <div class="card-header">
            <h3> Custom Fields</h3>
        </div>
        <div class="card-body">
            <hr>
            <div class="row mb-1 g-1">
                @forelse ($customFields as $field)
                    {!! $field !!}
                @empty
                @endforelse
            </div>
        </div>
    </div>
@endif

{{-- next-of-kin-list --}}
<div class="card" id="div-next-of-kin" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
    <div class="card-header">
        <h3>Next Of Kins </h3>
    </div>
    <div class="card-body">
        <div class="next-of-kin-list">
            <div data-repeater-list="next-of-kins">
                @forelse ((isset($stakeholder) && count($stakeholder->nextOfKin) > 0 ? $stakeholder->nextOfKin : old('next_of_kin')) ?? $emtyNextOfKin as $key => $KinData)

                    <div data-repeater-item>
                        <div class="card m-0">
                            <div class="card-header pt-0">
                                <h3>Next Of Kin</h3>

                                <button
                                    class="btn btn-relief-outline-danger waves-effect waves-float waves-light text-nowrap px-1"
                                    data-repeater-delete id="delete-next-of-kin" type="button">
                                    <i data-feather="x" class="me-25"></i>
                                    <span>Delete</span>
                                </button>
                            </div>
                            <div class="card-body">
                                <div>
                                    <div class="row mb-1">
                                        <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                            <label class="form-label" style="font-size: 15px"
                                                id="kin_{{ $key }}" for="stakeholder_type">Select Next Of
                                                Kin <span class="text-danger">*</span></label>
                                            <select class="form-control kinId uniqueKinId"
                                                id="kin_{{ $key }}"
                                                name="next_of_kin[{{ $key }}][stakeholder_id]">
                                                <option value="0" selected>Select Next Of Kin</option>
                                                @foreach ($stakeholders as $stakeholderssss)
                                                    <option value="{{ $stakeholderssss->id }}"
                                                        {{ isset($stakeholder) && count($stakeholder->nextOfKin) > 0 ? ($stakeholderssss->id == $KinData->kin_id ? 'selected' : '') : '' }}>
                                                        {{ $stakeholderssss->full_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                            <label class="form-label fs-5" id="relation_{{ $key }}"
                                                for="father_name">Relation</label>
                                            <input type="text"
                                                class="form-control form-control-md @error('relation') is-invalid @enderror"
                                                id="relation_{{ $key }}"
                                                value="{{ isset($stakeholder) && count($stakeholder->nextOfKin) > 0 ? $KinData->relation : '' }}"
                                                name="next_of_kin[{{ $key }}][relation]"
                                                placeholder="Relation" value="" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                @endforelse
            </div>
            <div class="row">
                <div class="col-12">
                    <button class="btn btn-relief-outline-primary waves-effect waves-float waves-light"
                        id="first-contact-person" type="button" data-repeater-create>
                        <i data-feather="plus" class="me-25"></i>
                        <span>Add New</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- contacts --}}
<div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
    <div class="card-header">
        <h3>Contact Persons</h3>

    </div>
    <div class="card-body">
        <div class="contact-persons-list">
            <div data-repeater-list="contact-persons">
                @forelse ((isset($stakeholder) && count($stakeholder->contacts) > 0 ? $stakeholder->contacts : old('contact-persons')) ?? $emptyRecord as $key => $oldContactPersons)
                    <div data-repeater-item>
                        <div class="card m-0">
                            <div class="card-header pt-0">
                                <h3>Contact Person</h3>

                                <button
                                    class="btn btn-relief-outline-danger waves-effect waves-float waves-light text-nowrap px-1"
                                    data-repeater-delete id="delete-contact-person" type="button">
                                    <i data-feather="x" class="me-25"></i>
                                    <span>Delete</span>
                                </button>
                            </div>
                            <div class="card-body">
                                <div>
                                    <div class="row mb-1">
                                        <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                                            <label class="form-label" style="font-size: 15px"
                                                for="stackholders">Stakeholders</label>
                                            <select class="form-select contact-person-select"
                                                data-id="{{ $key }}"
                                                name="contact-persons[{{ $key }}][stakeholder_contact_id]">
                                                <option value="0">Create new Stakeholder...</option>
                                                @forelse ($contactStakeholders as $cstakeholder)
                                                    @continue(isset($stakeholder) && $cstakeholder->id == $stakeholder->id)
                                                    <option value="{{ $cstakeholder->id }}"
                                                        {{ $oldContactPersons['stakeholder_contact_id'] == $cstakeholder->id ? 'selected' : '' }}>
                                                        {{ $cstakeholder->full_name }} s/o
                                                        {{ $cstakeholder->father_name }} {{ $cstakeholder->cnic }},
                                                        {{ $cstakeholder->contact }}
                                                    </option>
                                                @empty
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                            <label class="form-label fs-5" for="full_name_{{ $key }}">Full
                                                Name</label>
                                            <input type="text"
                                                class="form-control form-control-md @error('full_name') is-invalid @enderror"
                                                id="full_name_{{ $key }}"
                                                name="contact-persons[{{ $key }}][full_name]"
                                                placeholder="Stakeholder Name"
                                                value="{{ $oldContactPersons['full_name'] }}" />
                                        </div>

                                        <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                            <label class="form-label fs-5" for="father_name">Father / Husband
                                                Name</label>
                                            <input type="text"
                                                class="form-control form-control-md @error('father_name') is-invalid @enderror"
                                                id="father_name_{{ $key }}"
                                                name="contact-persons[{{ $key }}][father_name]"
                                                placeholder="Father / Husband Name"
                                                value="{{ $oldContactPersons['father_name'] }}" />
                                        </div>

                                        <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                            <label class="form-label fs-5" for="occupation">Occupation</label>
                                            <input type="text"
                                                class="form-control form-control-md @error('occupation') is-invalid @enderror"
                                                id="occupation_{{ $key }}"
                                                name="contact-persons[{{ $key }}][occupation]"
                                                placeholder="Occupation"
                                                value="{{ $oldContactPersons['occupation'] }}" />
                                        </div>

                                    </div>

                                    <div class="row mb-1">

                                        <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                            <label class="form-label fs-5" for="designation">Designation</label>
                                            <input type="text"
                                                class="form-control form-control-md @error('designation') is-invalid @enderror"
                                                id="designation_{{ $key }}"
                                                name="contact-persons[{{ $key }}][designation]"
                                                placeholder="Designation"
                                                value="{{ $oldContactPersons['designation'] }}" />
                                        </div>
                                        <input type="hidden"
                                            name="contact-persons[{{ $key }}][countryDetails]"
                                            id="countryDetails">

                                        <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                            <label class="form-label fs-5" for="contact">Contact</label>
                                            <input type="tel"
                                                class="form-control form-control-md intl @error('contact') is-invalid @enderror"
                                                id="contact_{{ $key }}"
                                                name="contact-persons[{{ $key }}][contact]"
                                                placeholder="Contact Number"
                                                value="{{ $oldContactPersons['contact'] }}" />
                                        </div>
                                    </div>

                                    <div class="row mb-1">

                                        <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                            <label class="form-label fs-5" for="cnic">CNIC</label>
                                            <input type="number"
                                                class="unique cp_cnic form-control form-control-md @error('cnic') is-invalid @enderror"
                                                id="cnic_{{ $key }}"
                                                name="contact-persons[{{ $key }}][cnic]"
                                                placeholder="CNIC Without Dashes"
                                                value="{{ $oldContactPersons['cnic'] }}" />
                                            @error('contact-persons.cnic')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                            <label class="form-label fs-5" for="ntn">NTN</label>
                                            <input type="number"
                                                class="form-control form-control-md @error('ntn') is-invalid @enderror"
                                                id="ntn_{{ $key }}"
                                                name="contact-persons[{{ $key }}][ntn]"
                                                placeholder="NTN Number" value="{{ $oldContactPersons['ntn'] }}" />
                                        </div>
                                    </div>

                                    <div class="row mb-1">
                                        <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                                            <label class="form-label fs-5" for="address">Stakeholder Address</label>
                                            <textarea class="form-control @error('address') is-invalid @enderror"
                                                name="contact-persons[{{ $key }}][address]" id="address_{{ $key }}" rows="3"
                                                placeholder="Stakeholder Address">{{ $oldContactPersons['address'] }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                @endforelse
            </div>
            <div class="row">
                <div class="col-12">
                    <button class="btn btn-relief-outline-primary waves-effect waves-float waves-light"
                        id="first-contact-person" type="button" data-repeater-create>
                        <i data-feather="plus" class="me-25"></i>
                        <span>Add New</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>


{{--  stakeholders in case of kins --}}

{{-- <div class="card" id="div-next-of-kin" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
    <div class="card-header">
        <h3>Stakeholders</h3>
    </div>
    <div class="card-body">
        <div class="next-of-kin-list">
            <div data-repeater-list="next-of-kins">
                @forelse ((isset($stakeholder) && count($stakeholder->nextOfKin) > 0 ? $stakeholder->nextOfKin : old('kinStakeholders')) ?? $emtykinStakeholders as $key => $stakeholderData)

                    <div data-repeater-item>
                        <div class="card m-0">
                            <div class="card-header pt-0">

                                <button
                                    class="btn btn-relief-outline-danger waves-effect waves-float waves-light text-nowrap px-1"
                                    data-repeater-delete id="delete-next-of-kin" type="button">
                                    <i data-feather="x" class="me-25"></i>
                                    <span>Delete</span>
                                </button>
                            </div>
                            <div class="card-body">
                                <div>
                                    <div class="row mb-1">
                                        <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                            <label class="form-label" style="font-size: 15px"
                                                id="kin_{{ $key }}" for="stakeholder_type">Select
                                                Stakeholder
                                                <span class="text-danger">*</span></label>
                                            <select class="form-control kinId uniqueKinId"
                                                id="kin_{{ $key }}"
                                                name="next_of_kin[{{ $key }}][stakeholder_id]">
                                                <option value="0" selected>Select Next Of Kin</option>
                                                @foreach ($stakeholders as $stakeholderssss)
                                                    <option value="{{ $stakeholderssss->id }}"
                                                        {{ isset($stakeholder) && count($stakeholder->nextOfKin) > 0 ? ($stakeholderssss->id == $KinData->kin_id ? 'selected' : '') : '' }}>
                                                        {{ $stakeholderssss->full_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                            <label class="form-label fs-5" id="relation_{{ $key }}"
                                                for="father_name">Relation</label>
                                            <input type="text"
                                                class="form-control form-control-md @error('relation') is-invalid @enderror"
                                                id="relation_{{ $key }}"
                                                value="{{ isset($stakeholder) && count($stakeholder->nextOfKin) > 0 ? $KinData->relation : '' }}"
                                                name="next_of_kin[{{ $key }}][relation]"
                                                placeholder="Relation" value="" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                @endforelse
            </div>
            <div class="row">
                <div class="col-12">
                    <button class="btn btn-relief-outline-primary waves-effect waves-float waves-light"
                        id="first-contact-person" type="button" data-repeater-create>
                        <i data-feather="plus" class="me-25"></i>
                        <span>Add New</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div> --}}

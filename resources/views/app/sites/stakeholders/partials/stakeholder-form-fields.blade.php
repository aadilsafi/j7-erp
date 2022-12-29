<div class="card mb-1"
    @if (!isset($hideBorders)) style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;" @endif>
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
        @if (!isset($hideBorders))

            <div class="py-1" id="stakeholderType">
                @if (!isset($stakeholder))
                    <div class="row mb-1">
                        <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                            <label class="form-label" style="font-size: 15px" for="stakeholder_type">Stakeholder Type
                                <span class="text-danger">*</span></label>
                            <select class="form-select form-select-lg select2" id="stakeholder_type"
                                name="stakeholder_type" {{ isset($stakeholder) ? 'disabled' : null }}>
                                <option value="0" selected>Select Stakeholder Type</option>
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
                                                {{ $type->status ? 'checked' : null }} />
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
        @endif
    </div>
</div>
@if (isset($hideBorders))
    <hr class="mx-4">
@endif

{{-- Company Form --}}

<div class="card" id="companyForm"
    @if (!isset($hideBorders)) style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;" @endif>
    <div class="card-header">
        <h3><strong>Company Informations: </strong></h3>
    </div>
    <div class="card-body">

        <div class="row mb-1">
            <div class="col-lg-6 col-md-6 position-relative">
                <label class="form-label fs-5" for="company_name">Company Name <span
                        class="text-danger">*</span></label>
                <input type="text" class="form-control form-control-md @error('full_name') is-invalid @enderror"
                    id="company_name" name="company[company_name]" placeholder="Company Name"
                    value="{{ isset($stakeholder) ? $stakeholder->full_name : old('company.company_name') }}" />
                @error('company.company_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-lg-6 col-md-6 position-relative">
                <label class="form-label fs-5" for="registration">Registration # <span
                        class="text-danger">*</span></label>
                <input type="text"
                    class="cp_cnic form-control form-control-md @error('registration') is-invalid @enderror"
                    id="registration" name="company[registration]" placeholder="Registration Number"
                    value="{{ isset($stakeholder) ? $stakeholder->cnic : old('company.registration') }}" />
                @error('company.registration')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row mb-1">
            <div class="col-lg-6 col-md-6 position-relative">
                <label class="form-label fs-5" for="industry">Industry <span class="text-danger">*</span></label>
                <input type="text" class="form-control form-control-md @error('industry') is-invalid @enderror"
                    id="industry" name="company[industry]" placeholder="Industry"
                    value="{{ isset($stakeholder) ? $stakeholder->industry : old('company.industry') }}" />
                @error('company.industry')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-lg-6 col-md-6 position-relative">
                <label class="form-label fs-5" for="strn">Origin </label>
                <select class="select2" id="origin" name="company[origin]">
                    <option value="0" selected>Select Company Origin</option>
                    @foreach ($country as $countryRow)
                        <option @if ((isset($stakeholder) && $stakeholder->origin) || old('company.origin') == $countryRow->id) selected @endif value="{{ $countryRow->id }}">
                            {{ $countryRow->name }}</option>
                    @endforeach
                </select>
                @error('company.origin')
                    <div class="invalid-feedback ">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row mb-1">
            <div class="col-lg-6 col-md-6 position-relative">
                <label class="form-label fs-5" for="company_ntn">NTN <span class="text-danger">*</span></label>
                <input type="text" class="form-control form-control-md @error('company_ntn') is-invalid @enderror"
                    id="company_ntn" name="company[company_ntn]" placeholder="NTN"
                    value="{{ isset($stakeholder) ? $stakeholder->ntn : old('company.company_ntn') }}" />
                @error('company.company_ntn')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-lg-6 col-md-6 position-relative">
                <label class="form-label fs-5" for="strn">STRN <span class="text-danger">*</span></label>
                <input type="number" class="form-control form-control-md @error('strn') is-invalid @enderror"
                    id="strn" name="company[strn]" placeholder="STRN"
                    value="{{ isset($stakeholder) ? $stakeholder->strn : old('company.strn') }}" />
                @error('company.strn')
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
                    id="company_office_contact" name="company[company_office_contact]" placeholder=""
                    value="{{ isset($stakeholder) ? $stakeholder->office_contact : old('company.company_office_contact') }}" />
                @error('company.company_office_contact')
                    <div class="invalid-feedback ">{{ $message }}</div>
                @enderror
            </div>

            <input type="hidden" name="CompanyOfficeContactCountryDetails" id="CompanyOfficeContactCountryDetails"
                value="{{ old('CompanyOfficeContactCountryDetails') }}">

            <div class="col-lg-6 col-md-6 col-sm-6">
                <label class="form-label fs-5" for="company_optional_contact">Optional Contact </label>
                <input type="tel"
                    class="form-control form-control-md OPTContactNoError contact @error('company_optional_contact') is-invalid @enderror"
                    id="company_optional_contact" name="company[company_optional_contact]" placeholder=""
                    value="{{ isset($stakeholder) ? $stakeholder->mobile_contact : old('company.company_optional_contact') }}" />
                @error('company.company_optional_contact')
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
                    id="company_email" name="company[company_email]" placeholder="Email" autocomplete="false"
                    value="{{ isset($stakeholder) ? $stakeholder->email : old('company.company_email') }}" />
                @error('company.company_email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-lg-6 col-md-6 position-relative">
                <label class="form-label fs-5" for="company_office_email">Office Email</label>
                <input type="email"
                    class="form-control form-control-md @error('office_email') is-invalid @enderror"
                    id="company_office_email" name="company[company_office_email]" placeholder="Office Email"
                    autocomplete="false"
                    value="{{ isset($stakeholder) ? $stakeholder->office_email : old('company.company_office_email') }}" />
                @error('company.company_office_email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row mb-1">
            <div class="col-lg-6 col-md-6 position-relative">
                <label class="form-label fs-5" for="website">Website </label>
                <input type="url" class="form-control form-control-md @error('website') is-invalid @enderror"
                    id="website" name="company[website]" placeholder="Website"
                    value="{{ isset($stakeholder) ? $stakeholder->website : old('company.website') }}" />
                @error('company.website')
                    <div class="invalid-feedback ">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-lg-6 col-md-6 position-relative">
                <label class="form-label fs-5" for="parent_company">Parent Company</label>
                <input type="text"
                    class="form-control form-control-md @error('parent_company') is-invalid @enderror"
                    id="parent_company" name="company[parent_company]" placeholder="Parent Company Name"
                    value="{{ isset($stakeholder) ? $stakeholder->parent_company : old('company.parent_company') }}" />
                @error('company.parent_company')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

    </div>
    @if (isset($hideBorders))
        <hr class="mx-4">
    @endif
</div>

{{-- Individual Form --}}
<div class="card" id="individualForm"
    @if (!isset($hideBorders)) style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;" @endif>
    <div class="card-header">
        <h3><strong>Individual Informations :</strong></h3>
    </div>
    <div class="card-body">

        <div class="row mb-1">
            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                <label class="form-label fs-5" for="full_name">Full Name<span class="text-danger">*</span></label>
                <input type="text" class="form-control form-control-md @error('full_name') is-invalid @enderror"
                    id="full_name" name="individual[full_name]" placeholder="Stakeholder Name"
                    value="{{ isset($stakeholder) ? $stakeholder->full_name : old('individual.full_name') }}" />
                @error('individual.full_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                <label class="form-label fs-5" for="father_name">Father / Husband Name <span
                        class="text-danger">*</span></label>
                <input type="text" class="form-control form-control-md @error('father_name') is-invalid @enderror"
                    id="father_name" name="individual[father_name]" placeholder="Father / Husband Name"
                    value="{{ isset($stakeholder) ? $stakeholder->father_name : old('individual.father_name') }}" />
                @error('individual.father_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                <label class="form-label fs-5" for="occupation">Occupation <span class="text-danger">*</span></label>
                <input type="text" class="form-control form-control-md @error('occupation') is-invalid @enderror"
                    id="occupation" name="individual[occupation]" placeholder="Occupation"
                    value="{{ isset($stakeholder) ? $stakeholder->occupation : old('individual.occupation') }}" />
                @error('individual.occupation')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="row mb-1">
            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                <label class="form-label fs-5" for="cnic">CNIC<span class="text-danger">*</span></label>
                <input type="text"
                    class="cp_cnic form-control form-control-md @error('cnic') is-invalid @enderror" id="cnic"
                    name="individual[cnic]" placeholder="CNIC Without Dashes"
                    value="{{ isset($stakeholder) ? $stakeholder->cnic : old('individual.cnic') }}" />
                @error('individual.cnic')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                <label class="form-label fs-5" for="passport_no">Passport No</label>
                <input type="text"
                    class="cp_cnic form-control form-control-md @error('passport_no') is-invalid @enderror"
                    id="passport_no" name="individual[passport_no]" placeholder="Passport No"
                    value="{{ isset($stakeholder) ? $stakeholder->passport_no : old('individual.passport_no') }}" />
                @error('individual.passport_no')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                <label class="form-label fs-5" for="ntn">NTN </label>
                <input type="number" class="form-control form-control-md @error('ntn') is-invalid @enderror"
                    id="ntn" name="individual[ntn]" placeholder="NTN Number"
                    value="{{ isset($stakeholder) ? $stakeholder->ntn : old('individual.ntn') }}" />
                @error('individual.ntn')
                    <div class="invalid-feedback ">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="row mb-1">
            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                <label class="form-label fs-5" for="individual_email">Email </label>
                <input type="email"
                    class="form-control form-control-md @error('individual_email') is-invalid @enderror"
                    id="individual_email" name="individual[individual_email]" placeholder="Email"
                    autocomplete="false"
                    value="{{ isset($stakeholder) ? $stakeholder->email : old('individual.individual_email') }}" />
                @error('individual.individual_email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                <label class="form-label fs-5" for="office_email">Office Email</label>
                <input type="email"
                    class="form-control form-control-md @error('office_email') is-invalid @enderror"
                    id="office_email" name="individual[office_email]" placeholder="Office Email"
                    autocomplete="false"
                    value="{{ isset($stakeholder) ? $stakeholder->office_email : old('individual.office_email') }}" />
                @error('individual.office_email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                <label class="form-label fs-5" for="designation">Designation </label>
                <input type="text" class="form-control form-control-md @error('designation') is-invalid @enderror"
                    id="designation" name="individual[designation]" placeholder="Designation"
                    value="{{ isset($stakeholder) ? $stakeholder->designation : old('individual.designation') }}" />
                @error('individual.designation')
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
                    id="mobile_contact" name="individual[mobile_contact]" placeholder=""
                    value="{{ isset($stakeholder) ? $stakeholder->mobile_contact : old('individual.mobile_contact') }}" />
                @error('individual.mobile_contact')
                    <div class="invalid-feedback ">{{ $message }}</div>
                @enderror
                <input type="hidden" name="mobileContactCountryDetails" id="mobileContactCountryDetails"
                    value="{{ old('mobileContactCountryDetails') }}">
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4">
                <label class="form-label fs-5" for="office_contact">Office Contact</label>
                <input type="tel"
                    class="form-control form-control-md OPTContactNoError optional_contact @error('office_contact') is-invalid @enderror"
                    id="office_contact" name="individual[office_contact]" placeholder=""
                    value="{{ isset($stakeholder) ? $stakeholder->office_contact : old('individual.office_contact') }}" />
                @error('individual.office_contact')
                    <div class="invalid-feedback ">{{ $message }}</div>
                @enderror
            </div>
            <input type="hidden" name="OfficeContactCountryDetails" id="OfficeContactCountryDetails"
                value="{{ old('OfficeContactCountryDetails') }}">
            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                <label class="form-label fs-5" for="dob">Date of Birth <span
                        class="text-danger">*</span></label>
                <input id="dob" type="date" required placeholder="YYYY-MM-DD" name="individual[dob]"
                    class="form-control form-control-md"
                    value="{{ isset($stakeholder) ? $stakeholder->date_of_birth : old('individual.dob') }}" />
                @error('individual.dob')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="row mb-1">
            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                <label class="form-label fs-5" for="referred_by">Refered By </label>
                <input type="text" class="form-control form-control-md @error('referred_by') is-invalid @enderror"
                    id="referred_by" name="individual[referred_by]" placeholder="Refered By" autocomplete="false"
                    value="{{ isset($stakeholder) ? $stakeholder->referred_by : old('individual.referred_by') }}" />
                @error('individual.referred_by')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                <label class="form-label" style="font-size: 15px" for="source">Lead
                    Source</label>
                <select class="form-select form-select-md select2" id="source" name="individual[source]">
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
                        <input type="checkbox" class="form-check-input" id="is_local" name="individual[is_local]"
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
                <select class="select2" id="nationality" name="individual[nationality]">
                    <option value="0" selected>Select Nationality</option>
                    @foreach ($country as $countryRow)
                        <option @if ((isset($stakeholder) && $stakeholder->nationality) || old('individual.nationality') == $countryRow->id) selected @endif value={{ $countryRow->id }}>
                            {{ $countryRow->name }}</option>
                    @endforeach
                </select>
                @error('individual.nationality')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
    @if (isset($hideBorders))
        <hr class="mx-4">
    @endif
</div>

{{-- Address fields --}}
<div class="card" id="common_form"
    @if (!isset($hideBorders)) style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;" @endif>
    <div class="card-header">
        <h3><strong>Address </strong></h3>
    </div>
    <div class="card-body">

        <div class="row mb-1">
            <div class="col">
                <h4 class="mb-1" id="change_residential_txt" class="change_residential_txt"><u>Residential
                        Address</u></h4>
                <span>‎</span>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative mb-1">
                        <label class="form-label" style="font-size: 15px" for="residential_address_type">Address
                            Type <span class="text-danger">*</span></label>
                        <input type="text"
                            class="form-control form-control-md @error('occupation') is-invalid @enderror"
                            id="residential_address_type" name="residential[address_type]" placeholder="Address Type"
                            value="{{ isset($stakeholder) ? $stakeholder->residential_address_type : old('residential.address_type') }}" />
                        @error('residential_address_type')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative mb-1">
                        <label class="form-label" style="font-size: 15px" for="residential_country">Select
                            Country <span class="text-danger">*</span></label>
                        <select class="select2" id="residential_country" name="residential[country]">
                            <option value="0" selected>Select Country</option>
                            @foreach ($country as $countryRow)
                                <option @if ((isset($stakeholder) && $stakeholder->residential_country_id) ||
                                    old('residential_country') == $countryRow->id) selected @endif
                                    value="{{ $countryRow->id }}">
                                    {{ $countryRow->name }}</option>
                            @endforeach
                        </select>
                        @error('residential.country')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative mb-1">
                        <label class="form-label" style="font-size: 15px" for="residential_state">Select
                            State <span class="text-danger">*</span></label>
                        <select class="select2" id="residential_state" name="residential[state]">
                            <option value="0" selected>Select State</option>

                        </select>
                        @error('residential.state')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative mb-1">
                        <label class="form-label" style="font-size: 15px" for="residential_city">Select City <span
                                class="text-danger">*</span></label>
                        <select class="select2" id="residential_city" name="residential[city]">
                            <option value="0" selected>Select City</option>
                        </select>
                        @error('residential.city')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative mb-1">
                        <label class="form-label" style="font-size: 15px" for="residential_postal_code">Postal Code
                            <span class="text-danger">*</span>
                        </label>
                        <input type="number"
                            class="form-control form-control-md @error('residential_postal_code') is-invalid @enderror"
                            id="residential_postal_code" name="residential[postal_code]" placeholder="Postal Code"
                            value="{{ isset($stakeholder) ? $stakeholder->residential_postal_code : old('residential_postal_code') }}" />
                        @error('residential.postal_code')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                        <label class="form-label fs-5" for="residential_address">Address <span
                                class="text-danger">*</span></label>
                        <textarea class="form-control @error('residential_address') is-invalid @enderror" name="residential[address]"
                            id="residential_address" rows="3" placeholder="Address">{{ isset($stakeholder) ? $stakeholder->residential_address : old('residential_address') }}</textarea>
                        @error('residential.address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="col">

                <h4 class="mb-1" id="change_mailing_txt" class="change_mailing_txt"><u>Mailing Address</u></h4>
                ( <span class="text-info" id="change_mailing_btn">Same as Residential Address </span> ) <input
                    type="checkbox" id="cpyAddress" />

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative mb-1">
                        <label class="form-label" style="font-size: 15px" for="mailing_address_type">Address
                            Type <span class="text-danger">*</span></label>
                        <input type="text"
                            class="form-control form-control-md @error('occupation') is-invalid @enderror"
                            id="mailing_address_type" name="mailing[address_type]" placeholder="Address Type"
                            value="{{ isset($stakeholder) ? $stakeholder->mailing_address_type : old('mailing.address_type') }}" />
                        @error('mailing_address_type')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative mb-1">
                        <label class="form-label" style="font-size: 15px" for="mailing_country">Select
                            Country <span class="text-danger">*</span></label>
                        <select class="select2" id="mailing_country" name="mailing[country]">
                            <option value="0" selected>Select Country</option>
                            @foreach ($country as $countryRow)
                                <option @if ((isset($stakeholder) && $stakeholder->mailing_country_id) == $countryRow->id) selected @endif
                                    value="{{ $countryRow->id }}">
                                    {{ $countryRow->name }}</option>
                            @endforeach
                        </select>
                        @error('mailing.country')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative mb-1">
                        <label class="form-label" style="font-size: 15px" for="mailing_state">Select State <span
                                class="text-danger">*</span></label>
                        <select class="select2" id="mailing_state" name="mailing[state]">
                            <option value="0" selected>Select State</option>
                        </select>
                        @error('mailing.state')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative mb-1">
                        <label class="form-label" style="font-size: 15px" for="mailing_city">Select City <span
                                class="text-danger">*</span></label>
                        <select class="select2" id="mailing_city" name="mailing[city]">
                            <option value="0" selected>Select City</option>
                        </select>
                        @error('mailing.city')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative mb-1">
                        <label class="form-label" style="font-size: 15px" for="mailing_postal_code">Postal Code <span
                                class="text-danger">*</span>
                        </label>
                        <input type="number"
                            class="form-control form-control-md @error('mailing_postal_code') is-invalid @enderror"
                            id="mailing_postal_code" name="mailing[postal_code]" placeholder="Postal Code"
                            value="{{ isset($stakeholder) ? $stakeholder->mailing_postal_code : old('mailing.postal_code') }}" />
                        @error('mailing.postal_code')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                        <label class="form-label fs-5" for="mailing_address">Address <span
                                class="text-danger">*</span></label>
                        <textarea class="form-control @error('mailing_address') is-invalid @enderror" name="mailing[address]"
                            id="mailing_address" rows="3" placeholder="Address">{{ isset($stakeholder) ? $stakeholder->mailing_address : old('mailing_address') }}</textarea>
                        @error('mailing.address')
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

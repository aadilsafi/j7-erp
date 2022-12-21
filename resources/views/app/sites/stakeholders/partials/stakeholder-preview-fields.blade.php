<div class="card mb-1"
    @if (!isset($hideBorders)) style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;" @endif>
    <div class="card-body">
        <div class="row mb-1">
            <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                <label class="form-label" style="font-size: 15px" for="stakeholder_as">Stakeholder As
                    <span class="text-danger">*</span></label>
                <select class="form-select form-select-lg" id="stakeholder_as" readonly>
                    <option value="0" selected>Select Stakeholder As</option>
                    <option value="i"
                        {{ isset($stakeholder) && $stakeholder->stakeholder_as == 'i' ? 'selected' : '' }}>Individual
                    </option>
                    <option value="c"
                        {{ isset($stakeholder) && $stakeholder->stakeholder_as == 'c' ? 'selected' : '' }}>Company
                    </option>
                </select>
                @error('stakeholder_as')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>
</div>
@if (isset($hideBorders))
    <hr class="mx-4">
@endif

{{-- Company Form --}}
@if (isset($stakeholder) && $stakeholder->stakeholder_as == 'c')
    <div class="card" id="companyForm"
        @if (!isset($hideBorders)) style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;" @endif>
        <div class="card-header">
            <h3><strong>Company informations: </strong></h3>
        </div>
        <div class="card-body">

            <div class="row mb-1">
                <div class="col-lg-6 col-md-6 position-relative">
                    <label class="form-label fs-5" for="company_name">Company Name <span
                            class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-md" id="company_name"
                        placeholder="Company Name" value="{{ $stakeholder->full_name }}" />
                </div>
                <div class="col-lg-6 col-md-6 position-relative">
                    <label class="form-label fs-5" for="registration">Registration # <span
                            class="text-danger">*</span></label>
                    <input type="text" class="cp_cnic form-control form-control-md" id="registration"
                        placeholder="Registration Number" value="{{ $stakeholder->cnic }}" />
                </div>
            </div>

            <div class="row mb-1">
                <div class="col-lg-6 col-md-6 position-relative">
                    <label class="form-label fs-5" for="industry">Industry <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-md" placeholder="Industry"
                        value="{{ $stakeholder->industry }}" />

                </div>
                <div class="col-lg-6 col-md-6 position-relative">
                    <label class="form-label fs-5" for="origin">Origin </label>
                    <input type="text" class="form-control form-control-md" placeholder="Origin"
                        value="{{ $stakeholder->originCountry->name ?? '' }}" />

                </div>
            </div>

            <div class="row mb-1">
                <div class="col-lg-6 col-md-6 position-relative">
                    <label class="form-label fs-5" for="company_ntn">NTN <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-md" placeholder="NTN"
                        value="{{ $stakeholder->ntn }}" />

                </div>
                <div class="col-lg-6 col-md-6 position-relative">
                    <label class="form-label fs-5" for="strn">STRN <span class="text-danger">*</span></label>
                    <input type="number" class="form-control form-control-md" placeholder="STRN"
                        value="{{ $stakeholder->strn }}" />
                </div>
            </div>

            <div class="row mb-1">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <label class="form-label fs-5" for="company_office_contact">Office Contact <span
                            class="text-danger">*</span></label>
                    <input type="tel" id="company_office_contact" class="form-control form-control-md"
                        placeholder="Office Contact" value="{{ $stakeholder->office_contact }}" />
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6">
                    <label class="form-label fs-5" for="company_optional_contact">Optional Contact </label>
                    <input type="tel" class="form-control form-control-md OPTContactNoError contact"
                        id="company_optional_contact" placeholder="Optional Contact"
                        value="{{ $stakeholder->mobile_contact }}" />
                </div>
            </div>

            <div class="row mb-1">
                <div class="col-lg-6 col-md-6 position-relative">
                    <label class="form-label fs-5" for="company_email">Email</label>
                    <input type="email" class="form-control form-control-md" id="company_email" placeholder="Email"
                        autocomplete="false" value="{{ $stakeholder->email }}" />

                </div>
                <div class="col-lg-6 col-md-6 position-relative">
                    <label class="form-label fs-5" for="company_office_email">Office Email</label>
                    <input type="email" class="form-control form-control-md" id="company_office_email"
                        placeholder="Office Email" autocomplete="false" value="{{ $stakeholder->office_email }}" />
                </div>
            </div>

            <div class="row mb-1">
                <div class="col-lg-6 col-md-6 position-relative">
                    <label class="form-label fs-5" for="website">Website </label>
                    <input type="url" class="form-control form-control-md" id="website" placeholder="Website"
                        value="{{ $stakeholder->website }}" />
                </div>

                <div class="col-lg-6 col-md-6 position-relative">
                    <label class="form-label fs-5" for="parent_company">Parent Company</label>
                    <input type="text" class="form-control form-control-md " id="parent_company"
                        placeholder="Parent Company Name" value="{{ $stakeholder->parent_company }}" />
                </div>
            </div>

        </div>
        @if (isset($hideBorders))
            <hr class="mx-4">
        @endif
    </div>
@endif
{{-- Individual Form --}}
@if (isset($stakeholder) && $stakeholder->stakeholder_as == 'i')

    <div class="card" id="individualForm"
        @if (!isset($hideBorders)) style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;" @endif>
        <div class="card-header">
            <h3><strong>Individual informations :</strong></h3>
        </div>
        <div class="card-body">

            <div class="row mb-1">
                <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                    <label class="form-label fs-5" for="full_name">Full Name<span
                            class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-md" id="full_name"
                        placeholder="Stakeholder Name" value="{{ $stakeholder->full_name ?? '' }}" />

                </div>

                <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                    <label class="form-label fs-5" for="father_name">Father / Husband Name <span
                            class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-md" id="father_name"
                        placeholder="Father / Husband Name" value="{{ $stakeholder->father_name ?? '' }}" />

                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                    <label class="form-label fs-5" for="occupation">Occupation <span
                            class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-md" id="occupation"
                        placeholder="Occupation" value="{{ $stakeholder->occupation ?? '' }}" />
                </div>
            </div>
            <div class="row mb-1">
                <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                    <label class="form-label fs-5" for="cnic">CNIC<span class="text-danger">*</span></label>
                    <input type="text" class="cp_cnic form-control form-control-md" id="cnic"
                        placeholder="CNIC" value="{{ isset($stakeholder) ? $stakeholder->cnic : '' }}" />

                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                    <label class="form-label fs-5" for="passport_no">Passport No</label>
                    <input type="text" class="cp_cnic form-control form-control-md" id="passport_no"
                        placeholder="Passport" value="{{ $stakeholder->passport_no ?? '' }}" />

                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                    <label class="form-label fs-5" for="ntn">NTN </label>
                    <input type="number" class="form-control form-control-md" id="ntn" name="individual[ntn]"
                        placeholder="NTN Number" value="{{ $stakeholder->ntn ?? '' }}" />
                </div>
            </div>
            <div class="row mb-1">
                <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                    <label class="form-label fs-5" for="individual_email">Email </label>
                    <input type="email" class="form-control form-control-md" id="individual_email"
                        name="individual[individual_email]" placeholder="Email" autocomplete="false"
                        value="{{ $stakeholder->email ?? '' }}" />

                </div>

                <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                    <label class="form-label fs-5" for="office_email">Office Email</label>
                    <input type="email" class="form-control form-control-md " id="office_email"
                        placeholder="Office Email" autocomplete="false"
                        value="{{ $stakeholder->office_email ?? '' }}" />

                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                    <label class="form-label fs-5" for="designation">Designation </label>
                    <input type="text" class="form-control form-control-md " id="designation"
                        name="individual[designation]" placeholder="Designation"
                        value="{{ $stakeholder->designation ?? '' }}" />

                </div>
            </div>
            <div class="row mb-1">
                <div class="col-lg-4 col-md-4 col-sm-4">
                    <label class="form-label fs-5" for="mobile_contact">Mobile Contact <span
                            class="text-danger">*</span></label>
                    <input type="tel" class="form-control form-control-md" id="mobile_contact"
                        placeholder="Mobile Contact" value="{{ $stakeholder->mobile_contact }}" />

                </div>
                <div class="col-lg-4 col-md-4 col-sm-4">
                    <label class="form-label fs-5" for="office_contact">Office Contact</label>
                    <input type="tel" class="form-control form-control-md" id="office_contact"
                        placeholder="Office Contact" value="{{ $stakeholder->office_contact }}" />

                </div>

                <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                    <label class="form-label fs-5" for="dob">Date of Birth <span
                            class="text-danger">*</span></label>
                    <input id="dob" type="date" placeholder="YYYY-MM-DD"
                        class="form-control form-control-md" value="{{ $stakeholder->date_of_birth }}" />

                </div>
            </div>
            <div class="row mb-1">
                <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                    <label class="form-label fs-5" for="referred_by">Refered By </label>
                    <input type="text" class="form-control form-control-md" id="referred_by"
                        placeholder="Refered By" value="{{ $stakeholder->referred_by }}" />
                </div>

                <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                    <label class="form-label" style="font-size: 15px" for="source">Lead
                        Source</label>
                    <input type="text" class="form-control form-control-md" id="source" placeholder="Source"
                        value="{{ $stakeholder->leadSource->name }}" />
                </div>
                <div class="col-1 col-md-1 col-lg-1 position-relative">
                    <div class="d-flex flex-column">
                        <label class="form-check-label mb-1" for="is_local"> Local<span
                                class="text-danger">*</span></label>
                        <div class="form-check form-switch form-check-primary">
                            <input type="checkbox" class="form-check-input" id="is_local"
                                name="individual[is_local]" value="1"
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
                    <input type="text" class="form-control form-control-md" id="nationality"
                        placeholder="nationality" value="{{ $stakeholder->nationalityCountry->name }}" />
                </div>
            </div>
        </div>
        @if (isset($hideBorders))
            <hr class="mx-4">
        @endif
    </div>
@endif
{{-- Address fields --}}
<div class="card" id="common_form"
    @if (!isset($hideBorders)) style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;" @endif>
    <div class="card-header">
        <h3><strong>Address </strong></h3>
    </div>
    <div class="card-body">

        <div class="row mb-1">
            <div class="col">
                <h4 class="mb-1" id="change_residential_txt"><u>
                        @if (isset($stakeholder) && $stakeholder->stakeholder_as == 'c')
                            Billing
                        @else
                            Residential
                        @endif Address
                    </u></h4>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative mb-1">
                        <label class="form-label" style="font-size: 15px" for="residential_address_type">Address
                            Type <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-md" id="residential_address_type"
                            placeholder="Address Type"
                            value="{{ isset($stakeholder) ? $stakeholder->residential_address_type : '' }}" />

                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative mb-1">
                        <label class="form-label" style="font-size: 15px" for="residential_country">
                            Country <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-md" id="residential_country"
                            placeholder="Country"
                            value="{{ isset($stakeholder) ? $stakeholder->residentialCountry->name : '' }}" />

                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative mb-1">
                        <label class="form-label" style="font-size: 15px" for="residential_state">
                            State <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-md" id="residential_state"
                            placeholder="Country"
                            value="{{ isset($stakeholder) ? $stakeholder->residentialState->name : '' }}" />
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative mb-1">
                        <label class="form-label" style="font-size: 15px" for="residential_city"> City <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-md" id="residential_city"
                            placeholder="City"
                            value="{{ isset($stakeholder) ? $stakeholder->residentialCity->name : '' }}" />

                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative mb-1">
                        <label class="form-label" style="font-size: 15px" for="residential_postal_code">Postal Code
                        </label>
                        <input type="number" class="form-control form-control-md" id="residential_postal_code"
                            placeholder="Postal Code"
                            value="{{ isset($stakeholder) ? $stakeholder->residential_postal_code : '' }}" />

                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                        <label class="form-label fs-5" for="residential_address">Residential Address <span
                                class="text-danger">*</span></label>
                        <textarea class="form-control" id="residential_address" rows="3" placeholder="Address">{{ isset($stakeholder) ? $stakeholder->residential_address : '' }}</textarea>

                    </div>
                </div>
            </div>
            <div class="col">

                <h4 class="mb-1" id="change_mailing_txt"><u>
                        @if (isset($stakeholder) && $stakeholder->stakeholder_as == 'c')
                            Shipping
                        @else
                            Mailing
                        @endif
                        Address
                    </u></h4>

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative mb-1">
                        <label class="form-label" style="font-size: 15px" for="mailing_address_type">Address
                            Type <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-md" id="mailing_address_type"
                            placeholder="Mailing Address Type"
                            value="{{ isset($stakeholder) ? $stakeholder->mailing_address_type : '' }}" />

                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative mb-1">
                        <label class="form-label" style="font-size: 15px" for="mailing_country">Select
                            Country <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-md" id="mailing_address_type"
                            placeholder="Mailing Address Type"
                            value="{{ isset($stakeholder) ? $stakeholder->mailing_address_type : '' }}" />
                           
                        </select>

                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative mb-1">
                        <label class="form-label" style="font-size: 15px" for="mailing_state">Select State <span
                                class="text-danger">*</span></label>
                        <select class="select2" id="mailing_state" name="mailing[state]">
                            <option value="0" selected>Select State</option>
                        </select>

                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative mb-1">
                        <label class="form-label" style="font-size: 15px" for="mailing_city">Select City <span
                                class="text-danger">*</span></label>
                        <select class="select2" id="mailing_city">
                            <option value="0" selected>Select City</option>
                        </select>

                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative mb-1">
                        <label class="form-label" style="font-size: 15px" for="mailing_postal_code">Postal Code
                        </label>
                        <input type="number" class="form-control form-control-md" id="mailing_postal_code"
                            placeholder="mailing Postal Code"
                            value="{{ isset($stakeholder) ? $stakeholder->mailing_postal_code : '' }}" />

                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                        <label class="form-label fs-5" for="mailing_address">Mailing Address <span
                                class="text-danger">*</span></label>
                        <textarea class="form-control" id="mailing_address" rows="3" placeholder="Stakeholder Address">{{ isset($stakeholder) ? $stakeholder->mailing_address : '' }}</textarea>

                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-1">
            <div class="col-lg col-md col-sm position-relative">
                <label class="form-label fs-5" for="comments">Comments</label>
                <textarea class="form-control @error('comments') is-invalid @enderror" name="comments" id="comments" rows="3"
                    placeholder="Comments">{{ isset($stakeholder) ? $stakeholder->comments : '' }}</textarea>

            </div>
        </div>
    </div>
</div>

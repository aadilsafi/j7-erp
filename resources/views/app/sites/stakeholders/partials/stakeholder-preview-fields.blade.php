{{-- Company Form --}}
@if (isset($stakeholder) && $stakeholder->stakeholder_as == 'c')
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
                    <input type="text" readonly class="form-control form-control-md " id="company_name"
                        name="company[company_name]" placeholder="Company Name"
                        value="{{ isset($stakeholder) ? $stakeholder->full_name : '' }}" />
                    @error('origin')
                        is-invalid
                    @enderror
                </div>
                <div class="col-lg-6 col-md-6 position-relative">
                    <label class="form-label fs-5" for="registration">Registration # <span
                            class="text-danger">*</span></label>
                    <input type="text" class="cp_cnic form-control form-control-md" id="registration"
                        name="company[registration]" placeholder="Registration Number" readonly
                        value="{{ isset($stakeholder) ? $stakeholder->cnic : old('company.registration') }}" />

                </div>
            </div>

            <div class="row mb-1">
                <div class="col-lg-6 col-md-6 position-relative">
                    <label class="form-label fs-5" for="industry">Industry <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-md " id="industry" name="company[industry]"
                        placeholder="Industry" readonly
                        value="{{ isset($stakeholder) ? $stakeholder->industry : old('company.industry') }}" />

                </div>
                <div class="col-lg-6 col-md-6 position-relative">
                    <label class="form-label fs-5" for="strn">Origin </label>
                    <input type="text" class="form-control form-control-md" id="origin" name="company[origin]"
                        placeholder="Origin" readonly
                        value="{{ isset($stakeholder) ? $stakeholder->originCountry->name : old('company.origin') }}" />

                </div>
            </div>

            <div class="row mb-1">
                <div class="col-lg-6 col-md-6 position-relative">
                    <label class="form-label fs-5" for="company_ntn">NTN <span class="text-danger">*</span></label>
                    <input type="text" readonly class="form-control form-control-md" id="company_ntn"
                        name="company[company_ntn]" placeholder="NTN"
                        value="{{ isset($stakeholder) ? $stakeholder->ntn : old('company.company_ntn') }}" />

                </div>
                <div class="col-lg-6 col-md-6 position-relative">
                    <label class="form-label fs-5" for="strn">STRN <span class="text-danger">*</span></label>
                    <input type="number" class="form-control form-control-md" id="strn" name="company[strn]"
                        placeholder="STRN" readonly
                        value="{{ isset($stakeholder) ? $stakeholder->strn : old('company.strn') }}" />

                </div>
            </div>

            <div class="row mb-1">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <label class="form-label fs-5" for="company_office_contact">Office Contact <span
                            class="text-danger">*</span></label>
                    <input type="tel" readonly class="form-control form-control-md ContactNoError optional_contact"
                        id="company_office_contact" name="company[company_office_contact]" placeholder=""
                        value="{{ isset($stakeholder) ? $stakeholder->office_contact : old('company.company_office_contact') }}" />

                </div>

                <div class="col-lg-6 col-md-6 col-sm-6">
                    <label class="form-label fs-5" for="company_optional_contact">Optional Contact </label>
                    <input type="tel" readonly class="form-control form-control-md OPTContactNoError contact"
                        id="company_optional_contact" name="company[company_optional_contact]" placeholder=""
                        value="{{ isset($stakeholder) ? $stakeholder->mobile_contact : old('company.company_optional_contact') }}" />
                </div>
            </div>

            <div class="row mb-1">
                <div class="col-lg-6 col-md-6 position-relative">
                    <label class="form-label fs-5" for="company_email">Email</label>
                    <input type="email" readonly class="form-control form-control-md" id="company_email"
                        name="company[company_email]" placeholder="Email" autocomplete="false"
                        value="{{ isset($stakeholder) ? $stakeholder->email : old('company.company_email') }}" />

                </div>
                <div class="col-lg-6 col-md-6 position-relative">
                    <label class="form-label fs-5" for="company_office_email">Office Email</label>
                    <input type="email" readonly class="form-control form-control-md " id="company_office_email"
                        name="company[company_office_email]" placeholder="Office Email" autocomplete="false"
                        value="{{ isset($stakeholder) ? $stakeholder->office_email : old('company.company_office_email') }}" />

                </div>
            </div>

            <div class="row mb-1">
                <div class="col-lg-6 col-md-6 position-relative">
                    <label class="form-label fs-5" for="website">Website </label>
                    <input type="url" class="form-control form-control-md" id="website"
                        name="company[website]" placeholder="Website" readonly
                        value="{{ isset($stakeholder) ? $stakeholder->website : old('company.website') }}" />

                </div>

                <div class="col-lg-6 col-md-6 position-relative">
                    <label class="form-label fs-5" for="parent_company">Parent Company</label>
                    <input type="text" readonly class="form-control form-control-md" id="parent_company"
                        name="company[parent_company]" placeholder="Parent Company Name"
                        value="{{ isset($stakeholder) ? $stakeholder->parent_company : old('company.parent_company') }}" />

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
            <h3><strong>Individual Informations :</strong></h3>
        </div>
        <div class="card-body">

            <div class="row mb-1">
                <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                    <label class="form-label fs-5" for="full_name">Full Name<span
                            class="text-danger">*</span></label>
                    <input type="text" readonly class="form-control form-control-md" id="full_name"
                        name="individual[full_name]" placeholder="Stakeholder Name"
                        value="{{ isset($stakeholder) ? $stakeholder->full_name : old('individual.full_name') }}" />
                </div>

                <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                    <label class="form-label fs-5" for="father_name">Father / Husband Name <span
                            class="text-danger">*</span></label>
                    <input type="text" readonly class="form-control form-control-md" id="father_name"
                        name="individual[father_name]" placeholder="Father / Husband Name"
                        value="{{ isset($stakeholder) ? $stakeholder->father_name : old('individual.father_name') }}" />
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                    <label class="form-label fs-5" for="occupation">Occupation <span
                            class="text-danger">*</span></label>
                    <input type="text" readonly class="form-control form-control-md" id="occupation"
                        name="individual[occupation]" placeholder="Occupation"
                        value="{{ isset($stakeholder) ? $stakeholder->occupation : '' }}" />
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                    <label class="form-label fs-5" for="designation">Designation </label>
                    <input type="text" readonly
                        class="form-control form-control-md @error('designation') is-invalid @enderror"
                        id="designation" name="individual[designation]" placeholder="Designation"
                        value="{{ isset($stakeholder) ? $stakeholder->designation : '' }}" />
                </div>
            </div>
            <div class="row mb-1">
                <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                    <label class="form-label fs-5" for="cnic">CNIC<span class="text-danger">*</span></label>
                    <input type="text" readonly class=" form-control form-control-md" id="cnic"
                        name="individual[cnic]" placeholder="CNIC Without Dashes"
                        value="{{ isset($stakeholder) ? $stakeholder->cnic : old('individual.cnic') }}" />
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                    <label class="form-label fs-5" for="passport_no">Passport No</label>
                    <input type="text" readonly class=" form-control form-control-md" id="passport_no"
                        name="individual[passport_no]" placeholder="Passport No"
                        value="{{ isset($stakeholder) ? $stakeholder->passport_no : old('individual.passport_no') }}" />
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                    <label class="form-label fs-5" for="ntn">NTN </label>
                    <input type="number" class="form-control form-control-md" readonly id="ntn"
                        name="individual[ntn]" placeholder="NTN Number"
                        value="{{ isset($stakeholder) ? $stakeholder->ntn : old('individual.ntn') }}" />
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                    <label class="form-label fs-5" for="dob">Date of Birth <span
                            class="text-danger">*</span></label>
                    <input id="dob" type="date" readonly placeholder="YYYY-MM-DD" name="individual[dob]"
                        class="form-control form-control-md"
                        value="{{ isset($stakeholder) ? $stakeholder->date_of_birth : old('individual.dob') }}" />

                </div>
            </div>
            <div class="row mb-1">
                <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                    <label class="form-label fs-5" for="individual_email">Email </label>
                    <input type="email" readonly class="form-control form-control-md" id="individual_email"
                        name="individual[individual_email]" placeholder="Email" autocomplete="false"
                        value="{{ isset($stakeholder) ? $stakeholder->email : old('individual.individual_email') }}" />

                </div>

                <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                    <label class="form-label fs-5" for="office_email">Office Email</label>
                    <input type="email" readonly class="form-control form-control-md" id="office_email"
                        name="individual[office_email]" placeholder="Office Email" autocomplete="false"
                        value="{{ isset($stakeholder) ? $stakeholder->office_email : old('individual.office_email') }}" />
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3">
                    <label class="form-label fs-5" for="mobile_contact">Mobile Contact <span
                            class="text-danger">*</span></label>
                    <input type="tel" readonly class="form-control form-control-md" id="mobile_contact"
                        name="individual[mobile_contact]" placeholder=""
                        value="{{ isset($stakeholder) ? $stakeholder->mobile_contact : old('individual.mobile_contact') }}" />
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3">
                    <label class="form-label fs-5" for="office_contact">Office Contact</label>
                    <input type="tel" readonly
                        class="form-control form-control-md OPTContactNoError optional_contact @error('office_contact') is-invalid @enderror"
                        id="office_contact" name="individual[office_contact]" placeholder=""
                        value="{{ isset($stakeholder) ? $stakeholder->office_contact : old('individual.office_contact') }}" />
                </div>
            </div>

            <div class="row mb-1">
                <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                    <label class="form-label fs-5" for="referred_by">Refered By </label>
                    <input type="text" readonly class="form-control form-control-md" id="referred_by"
                        name="individual[referred_by]" placeholder="Refered By" autocomplete="false"
                        value="{{ isset($stakeholder) ? $stakeholder->referred_by : old('individual.referred_by') }}" />
                </div>

                <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                    <label class="form-label" style="font-size: 15px" for="source">Lead
                        Source</label>
                    <input type="text" readonly class="form-control form-control-md" id="source"
                        name="individual[source]" placeholder="Refered By" autocomplete="false"
                        value="{{ isset($stakeholder) ? $stakeholder->leadSource->name ?? '' : '' }}" />
                </div>

                <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                    <label class="form-label" style="font-size: 15px" for="source">Local / Overseas
                    </label>
                    <input type="text" readonly class="form-control form-control-md" id="source"
                        name="individual[source]" placeholder="Refered By" autocomplete="false"
                        value="{{ isset($stakeholder) && $stakeholder->is_local ? 'Local' : 'Overseas' }}" />
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                    <label class="form-label fs-5" for="nationality">Nationality <span
                            class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-md" id="nationality" readonly
                        placeholder="nationality" value="{{ $stakeholder->nationalityCountry->name }}" />
                </div>
            </div>
        </div>
        @if (isset($hideBorders))
            <hr class="mx-1">
        @endif
    </div>
@endif
{{-- Address fields --}}
<div class="card" id="common_form"
    @if (!isset($hideBorders)) style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;" @endif>
    <div class="card-header">
        <h3><strong>Address:</strong></h3>
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
                        <label class="form-label" style="font-size: 15px" for="residential_country">
                            Country <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-md" id="residential_country"
                            placeholder="Country" readonly
                            value="{{ isset($stakeholder) ? $stakeholder->residentialCountry->name ?? '': '' }}" />
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative mb-1">
                        <label class="form-label" style="font-size: 15px" for="residential_state">
                            State <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-md" id="residential_state"
                            placeholder="Country" readonly
                            value="{{ isset($stakeholder) ? $stakeholder->residentialState->name ?? '': '' }}" />
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative mb-1">
                        <label class="form-label" style="font-size: 15px" for="residential_city"> City <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-md" id="residential_city"
                            placeholder="City" readonly
                            value="{{ isset($stakeholder) ? $stakeholder->residentialCity->name ?? '': '' }}" />
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 position-relative mb-1">
                        <label class="form-label" style="font-size: 15px" for="residential_address_type">Address
                            Type <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-md" id="residential_address_type"
                            placeholder="Address Type" readonly
                            value="{{ isset($stakeholder) ? $stakeholder->residential_address_type : '' }}" />
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-6 position-relative mb-1">
                        <label class="form-label" style="font-size: 15px" for="residential_postal_code">Postal Code
                        </label>
                        <input type="number" class="form-control form-control-md" id="residential_postal_code"
                            placeholder="Postal Code" readonly
                            value="{{ isset($stakeholder) ? $stakeholder->residential_postal_code : '' }}" />

                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                        <label class="form-label fs-5" for="residential_address">Residential Address <span
                                class="text-danger">*</span></label>
                        <textarea class="form-control" id="residential_address" readonly rows="3" placeholder="Address">{{ isset($stakeholder) ? $stakeholder->residential_address : '' }}</textarea>

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
                        <label class="form-label" style="font-size: 15px" for="mailing_country">
                            Country <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-md" id="mailing_country"
                            placeholder="Country" readonly
                            value="{{ isset($stakeholder) ? $stakeholder->mailingCountry->name ?? '' : '' }}" />
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative mb-1">
                        <label class="form-label" style="font-size: 15px" for="mailing_state"> State <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-md" id="mailing_state"
                            placeholder="Country" readonly
                            value="{{ isset($stakeholder) ? $stakeholder->mailingState->name ?? '' : '' }}" />
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative mb-1">
                        <label class="form-label" style="font-size: 15px" for="mailing_city"> City <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-md" id="mailing_city"
                            placeholder="Country" readonly
                            value="{{ isset($stakeholder) ? $stakeholder->mailingCountry->name ?? '': '' }}" />
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 position-relative mb-1">
                        <label class="form-label" style="font-size: 15px" for="mailing_address_type">Address
                            Type <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-md" id="mailing_address_type"
                            placeholder="Mailing Address Type" readonly
                            value="{{ isset($stakeholder) ? $stakeholder->mailing_address_type ?? '' : '' }}" />

                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 position-relative mb-1">
                        <label class="form-label" style="font-size: 15px" for="mailing_postal_code">Postal Code
                            <span class="text-danger">*</span>
                        </label>
                        <input type="number"
                            class="form-control form-control-md @error('mailing_postal_code') is-invalid @enderror"
                            id="mailing_postal_code" name="mailing[postal_code]" placeholder="Mailing Postal Code"
                            readonly
                            value="{{ isset($stakeholder) ? $stakeholder->mailing_postal_code : old('mailing.postal_code') }}" />

                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                        <label class="form-label fs-5" for="mailing_address">Mailing Address <span
                                class="text-danger">*</span></label>
                        <textarea class="form-control @error('mailing_address') is-invalid @enderror" name="mailing[address]"
                            id="mailing_address" rows="3" readonly placeholder="Stakeholder Address">{{ isset($stakeholder) ? $stakeholder->mailing_address : old('mailing_address') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-1">
            <div class="col-lg col-md col-sm position-relative">
                <label class="form-label fs-5" for="comments">Comments</label>
                <textarea class="form-control @error('comments') is-invalid @enderror" name="comments" id="comments" rows="3"
                    placeholder="Comments" readonly>{{ isset($stakeholder) ? $stakeholder->comments : old('comments') }}</textarea>

            </div>
        </div>
    </div>
</div>

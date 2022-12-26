<div class="row mb-1">
    <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
        <div class="card m-0" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
            <div class="card-body">
                <div class="row mb-1">
                    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                        <label class="form-label" style="font-size: 15px" for="unit_id">
                            <h6 class="mb-1">Select Unit</h6>
                        </label>
                        <select id="unit_id" @if (isset($edit_unit)) disabled @endif
                            class="select2 form-select  unit_id @error('unit_id') is-invalid @enderror" name="unit_id"
                            onchange="getData(this.options[this.selectedIndex].value)">
                            @if (isset($edit_unit))
                                <option value="{{ $edit_unit->id }}">{{ $edit_unit->name }} (
                                    {{ $edit_unit->floor_unit_number }} -
                                    {{ $edit_unit->floor->name }} - {{ $edit_unit->type->name }})</option>
                            @else
                                <option>Select Unit No</option>

                                @foreach ($units as $row)
                                    {{-- @continue(isset($rebate_files) && in_array($row->id, $rebate_files)) --}}
                                    <option value="{{ $row->id }}">
                                        {{ $row->name }} ( {{ $row->floor_unit_number }} -
                                        {{ $row->floor->name }} - {{ $row->type->name }})
                                    </option>
                                @endforeach
                            @endif

                        </select>
                        @error('unit_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                        <label class="form-label" style="font-size: 15px" for="floor">
                            <h6 class="mb-1">Rebate %</h6>
                        </label>
                        <input id="rebate_percentage" type="text"
                            value="{{ isset($rebate_data) ? $rebate_data->commision_percentage : '' }}"
                            class="form-control rebate_percentage  @error('rebate_percentage') is-invalid @enderror"
                            name="rebate_percentage" placeholder="Rebate Percentage">
                        @error('rebate_percentage')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

{{-- Sales Person Data --}}
<div class="row mb-1 hideDiv">
    <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
        <div class="card m-0" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
            <div class="card-header">
                <h3>Sales Person</h3>
            </div>

            <div class="card-body">

                <div class="row mb-1">
                    <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                        <label class="form-label fs-5" for="sales_source_full_name">Sales
                            Person</label>
                        <input type="text" class="form-control form-control-lg" id="sales_source_full_name"
                            name="sales_source_name" placeholder="Sales Person" value="{{ Auth::user()->name }}"
                            disabled />
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                        @php
                            $roles = Auth::user()
                                ->roles->pluck('name')
                                ->toArray();
                            $roles = implode(', ', $roles);
                        @endphp

                        <label class="form-label fs-5" for="sales_source_status">Status</label>
                        <input type="text" class="form-control form-control-lg" id="sales_source_status"
                            name="sales_source_role" placeholder="Status" value="{{ $roles }}" disabled />
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                        <label class="form-label fs-5" for="sales_source_contact_no">Contact
                            No</label>
                        <input type="text" class="form-control form-control-lg" id="sales_source_contact_no"
                            name="sales_source_contact_no" placeholder="Contact No"
                            value="{{ Auth::user()->phone_no }}" disabled />

                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                        <label class="form-label fs-5" for="sales_source_lead_source">Lead
                            Source</label>
                        <input type="text" name="sales_source_lead_source" class="form-control form-control-lg"
                            id="sales_source_lead_source" placeholder="Lead Source" value="" disabled />
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- Dealer Data --}}
<div class="row mb-1 hideDiv">
    <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
        <div class="card m-0" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;"
            id="stakeholders_card">
            <div class="card-header">
                <h3>Dealer</h3>
            </div>

            <div class="card-body">

                <div class="row g-1 mb-1">

                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                        <label class="form-label" style="font-size: 15px" for="dealer">Dealer</label>
                        <select class="form-select form-select-lg" id="dealer" name="dealer_id">
                            <option value="0">Create new Dealer</option>
                            @forelse ($dealer_data as $dealer)
                                <option value="{{ $dealer->stakeholder->id }}"
                                    {{ isset($rebate_data) && $rebate_data->dealer_id == $dealer->stakeholder->id ? 'selected' : '' }}>
                                    {{ $dealer->stakeholder->full_name }} ( {{ $dealer->stakeholder->cnic }} )
                                    - {{ $dealer->stakeholder->designation }}
                                </option>
                            @empty
                            @endforelse
                        </select>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-6 position-relative">
                        <div id="div_new_dealer">
                            <input type="hidden" name="dealer[type]" value="D">

                            {{--  individual Form --}}
                            <div id="individualForm">
                                <div class="row mb-1">
                                    <div class="col-lg-4 col-md-4 col-sm-12 position-relative">
                                        <label class="form-label fs-5" for="stackholder_full_name">Full Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-lg"
                                            id="stackholder_full_name" name="dealer[full_name]"
                                            placeholder="Full Name" value="{{ old('stackholder.full_name') }}" />
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-12 position-relative">
                                        <label class="form-label fs-5" for="stackholder_father_name">Father / Husband
                                            Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-lg"
                                            id="stackholder_father_name" name="dealer[father_name]"
                                            placeholder="Father / Husband Name"
                                            value="{{ old('stackholder.father_name') }}" />
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-12 position-relative">
                                        <label class="form-label fs-5" for="stackholder_occupation">Occupation</label>
                                        <input type="text" class="form-control form-control-lg"
                                            id="stackholder_occupation" name="dealer[occupation]"
                                            placeholder="Occupation" value="{{ old('stackholder.occupation') }}" />
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-lg-4 col-md-4 col-sm-12 position-relative">
                                        <label class="form-label fs-5"
                                            for="stackholder_designation">Designation</label>
                                        <input type="text" class="form-control form-control-lg"
                                            id="stackholder_designation" name="dealer[designation]"
                                            placeholder="Designation" value="{{ old('stackholder.designation') }}" />
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-12 position-relative">
                                        <label class="form-label fs-5" for="stackholder_ntn">NTN</label>
                                        <input type="number" class="form-control form-control-lg"
                                            id="stackholder_ntn" name="dealer[ntn]" placeholder="NTN"
                                            value="{{ old('stackholder.ntn') }}" />
                                    </div>

                                    <div class="col-lg-4 col-md-4 col-sm-12 position-relative">
                                        <label class="form-label fs-5" for="stackholder_cnic">CNIC <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-lg"
                                            id="stackholder_cnic" name="dealer[cnic]" placeholder="CNIC"
                                            value="{{ old('stackholder.cnic') }}" />
                                    </div>
                                </div>
                            </div>

                            {{-- company form --}}
                            <div id="companyForm">
                                <div class="row mb-1">
                                    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                        <label class="form-label fs-5" for="company_name">Company Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" readonly
                                            class="form-control form-control-lg @error('full_name') is-invalid @enderror"
                                            id="company_name" placeholder="Company Name"
                                            value="{{ old('full_name') }}" />
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                        <label class="form-label fs-5" for="industry">Industry </label>
                                        <input type="text" readonly
                                            class="form-control form-control-lg @error('industry') is-invalid @enderror"
                                            id="industry" placeholder="Industry" value="{{ old('industry') }}" />
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                        <label class="form-label fs-5" for="registration">Registration # <span
                                                class="text-danger">*</span></label>
                                        <input type="text" readonly
                                            class="cp_cnic form-control form-control-lg @error('registration') is-invalid @enderror"
                                            id="registration" placeholder="Registration Number"
                                            value="{{ old('registration') }}" />

                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                        <label class="form-label fs-5" for="ntn">NTN </label>
                                        <input type="number" readonly
                                            class="form-control form-control-lg @error('ntn') is-invalid @enderror"
                                            id="ntn" placeholder="NTN Number" value="{{ old('ntn') }}" />

                                    </div>
                                </div>
                            </div>

                            {{-- common form  --}}
                            <div class="row mb-1">
                                <div class="col-lg-3 col-md-3 col-sm-12 position-relative">
                                    <label class="form-label fs-5" for="email">Email <span
                                            class="text-danger">*</span></label>
                                    <input type="email"
                                        class="form-control form-control-lg @error('email') is-invalid @enderror"
                                        id="stackholder_email" name="dealer[email]" placeholder="Email"
                                        autocomplete="false" value="{{ old('stackholder.email') }}" />
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-lg-3 col-md-3 col-sm-12 position-relative">
                                    <label class="form-label fs-5" for="optional_email">Optional Email</label>
                                    <input type="email"
                                        class="form-control form-control-lg @error('email') is-invalid @enderror"
                                        id="stackholder_optional_email" name="dealer[optional_email]"
                                        placeholder="Optional Email" autocomplete="false"
                                        value="{{ old('stackholder.optional_email') }}" />
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-lg-3 col-md-3 col-sm-12 position-relative">
                                    <label class="form-label fs-5" for="stackholder_contact">Contact <span
                                            class="text-danger">*</span></label>
                                    <input type="tel" class="form-control form-control-lg ContactNoError"
                                        id="stackholder_contact" name="dealer[contact]" placeholder=""
                                        value="{{ old('stackholder.contact') }}" />
                                </div>
                                <input type="hidden" name="dealer[countryDetails]" id="countryDetails">

                                <div class="col-lg-3 col-md-3 col-sm-12">
                                    <label class="form-label fs-5" for="contact">Optional Contact # </label>
                                    <input type="tel"
                                        class="form-control form-control-lg OPTContactNoError @error('contact') is-invalid @enderror"
                                        id="optional_contact" name="dealer[optional_contact]" placeholder=""
                                        value="{{ old('stackholder.optional_contact') }}" />
                                    @error('optional_contact')
                                        <div class="invalid-feedback ">{{ $message }}</div>
                                    @enderror
                                </div>
                                <input type="hidden" name="dealer[OptionalCountryDetails]"
                                    id="OptionalCountryDetails">
                            </div>
                            <div class="row mb-1">

                                <div class="col-lg-3 col-md-3 col-sm-6 position-relative">
                                    <label class="form-label" style="font-size: 15px" for="parent_id">Select
                                        Country</label>
                                    <select class="select2 country_id" id="country_id"
                                        name="dealer[country_id]">
                                        <option value="0" selected>Select Country</option>
                                        @foreach ($country as $data)
                                            <option value="{{ $data->id }}">{{ $data->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('country_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-lg-3 col-md-3 col-sm-6 position-relative">
                                    <label class="form-label" style="font-size: 15px" for="city_id">Select
                                        State</label>
                                    <select class="select2 state_id" id="state_id" name="dealer[state_id]">
                                        <option value="0" selected>Select State</option>

                                    </select>
                                    @error('state_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-lg-3 col-md-3 col-sm-6 position-relative">
                                    <label class="form-label" style="font-size: 15px" for="city_id">Select
                                        City</label>
                                    <select class="select2 city_id" id="city_id" name="dealer[city_id]">
                                        <option value="0" selected>Select City</option>

                                    </select>
                                    @error('city_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 position-relative">
                                    <label class="form-label fs-5" for="occupation">Nationality </label>
                                    <input type="text"
                                        class="form-control form-control-lg @error('occupation') is-invalid @enderror"
                                        id="nationality" name="nationality" placeholder="Nationality"
                                        value="{{ old('nationality') }}" />
                                    @error('nationality')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-lg-6 col-md-6 col-sm-12 position-relative">
                                    <label class="form-label fs-5" for="stackholder_address">Address <span
                                            class="text-danger">*</span></label>
                                    <textarea class="form-control form-control-lg" id="stackholder_address" name="dealer[address]"
                                        placeholder="Address" rows="4"></textarea>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 position-relative">
                                    <label class="form-label fs-5" for="mailing_address">Mailing Address <span
                                            class="text-danger">*</span><span class="text-info">( Same as Permanent
                                            Address
                                            <input type="checkbox" id="cpyAddress" />
                                            )</span></label>
                                    <textarea class="form-control form-control-lg" id="mailing_address" name="dealer[mailing_address]"
                                        placeholder="Mailing Address" rows="4"></textarea>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-lg- col-md- col-sm-12 position-relative">
                                    <label class="form-label fs-5" for="stackholder_comments">Comments</label>
                                    <textarea class="form-control form-control-lg" id="stackholder_comments" name="dealer[comments]"
                                        placeholder="Comments" rows="4"></textarea>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Stakeholder Data --}}
<div class="row mb-1 hideDiv">
    <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
        <div class="card m-0" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
            <div class="card-header">
                <h3>Customer</h3>
            </div>

            <div class="card-body">

                <div class="row g-1 mb-1">
                    <input id="stakeholder_id" type="hidden" name="stakeholder_id" value="">

                    <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                        <label class="form-label fs-5" for="customer_name">Name</label>
                        <input type="text" class="form-control form-control-lg" id="customer_name"
                            placeholder="Name" value="" disabled />
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                        <label class="form-label fs-5" for="customer_father_name">Father/Husband
                            Name</label>
                        <input type="text" class="form-control form-control-lg" id="customer_father_name"
                            value="" placeholder="Father/Husband Name" disabled />
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                        <label class="form-label fs-5" for="customer_occupation">Occupation</label>
                        <input type="text" class="form-control form-control-lg" id="customer_occupation"
                            placeholder="Occupation" value="" disabled />
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                        <label class="form-label fs-5" for="customer_occupation">Designation</label>
                        <input type="text" class="form-control form-control-lg" id="customer_designation"
                            placeholder="Designation" value="" disabled />
                    </div>
                </div>

                <div class="row g-1 mb-1">
                    <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                        <label class="form-label fs-5" for="customer_ntn">NTN</label>
                        <input type="text" class="form-control form-control-lg" id="customer_ntn"
                            placeholder="NTN" value="" disabled />
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                        <label class="form-label fs-5" for="customer_cnic">CNIC/Passport</label>
                        <input type="text" class="form-control form-control-lg" id="customer_cnic"
                            placeholder="CNIC/Passport" value="" disabled />
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                        <label class="form-label fs-5" for="customer_phone">Contact NO#</label>
                        <input type="text" class="form-control form-control-lg" id="customer_phone"
                            placeholder="Cell" value="" disabled />
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                        <label class="form-label fs-5" for="customer_phone">Optional Contact NO#</label>
                        <input type="text" class="form-control form-control-lg" id="optional_customer_phone"
                            placeholder="Cell" value="" disabled />
                    </div>

                </div>

                <div class="row g-1 mb-1">
                    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                        <label class="form-label fs-5" for="customer_address">Address</label>
                        <input type="text" class="form-control form-control-lg" id="customer_address"
                            placeholder="Address" value="" disabled />
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                        <label class="form-label fs-5" for="customer_address">Mailing Address</label>
                        <input type="text" class="form-control form-control-lg" id="customer_mailing_address"
                            placeholder="Address" value="" disabled />
                    </div>

                </div>

                <div class="row g-1 mb-1">
                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                        <label class="form-label fs-5" for="customer_comments">Comments</label>
                        <input type="text" class="form-control form-control-lg" id="customer_comments"
                            placeholder="Comments" value="" disabled />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- PAYMENT PLAN --}}
<div class="card hideDiv" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;"
    id="installments_acard">
    <div class="card-header">
        <h3>Unit Details</h3>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                <div class="table-responsive">
                    <table class="table table-hover table-striped table-borderless" id="installments_table"
                        style="position: relative;">
                        <thead style="position: sticky; top: 0; z-index: 10;">

                            <tr class="text-center">
                                <th style="vertical-align: middle;" rowspan="2" scope="col">Sr
                                </th>
                                <th style="vertical-align: middle;" rowspan="2" scope="col">
                                    Unit #
                                </th>
                                <th style="vertical-align: middle;" rowspan="2" scope="col">
                                    Area
                                </th>
                                <th style="vertical-align: middle;" rowspan="2" scope="col">
                                    Rate
                                </th>
                                <th id="floor" style="vertical-align: middle;" rowspan="2" scope="col">
                                    Floor
                                </th>
                                <th id="faceCharges" class="text-nowrap" style="vertical-align: middle;"
                                    scope="col">Face Charges</th>
                                <th style="vertical-align: middle;" scope="col">Discount</th>
                                <th style="vertical-align: middle;" scope="col">Total</th>
                                <th style="vertical-align: middle;" scope="col">Downpayment</th>
                                <th style="vertical-align: middle;" scope="col">Rebate</th>
                            </tr>

                            <tr class="text-center">
                                <th id="faceChargesPercentage" style="vertical-align: middle;" scope="col">%</th>
                                <th style="vertical-align: middle;" scope="col"> %</th>
                                <th style="vertical-align: middle;" scope="col">Value</th>
                                <th style="vertical-align: middle;" scope="col"> %</th>
                                <th style="vertical-align: middle;" scope="col">%</th>
                            </tr>
                            <input type="hidden" value="" id="unit_total">
                            <input type="hidden" value="" id="rebate_total" name="rebate_total">
                        </thead>

                        <tbody>
                            <tr class="text-center">
                                <td>1</td>
                                <td id="td_unit_id">-</td>
                                <td id="td_unit_area">-</td>
                                <td id="td_unit_rate">-</td>
                                <td id="td_unit_floor">-</td>
                                <td id="td_unit_facing_charges">-</td>
                                <td id="td_unit_discount">-</td>
                                <td id="td_unit_total">-</td>
                                <td id="td_unit_downpayment">-</td>
                                <td id="td_rebate">-</td>
                            </tr>
                            <tr class="text-center">
                                <td colspan="5">
                                    <h4>Grand Total</h4>
                                </td>
                                <td id="td_unit_facing_charges_value">-</td>
                                <td id="td_unit_discount_value">-</td>
                                <td id="td_unit_total_value">-</td>
                                <td id="td_unit_downpayment_value">-</td>
                                <td id="td_rebate_value">-</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="col-lg-12 col-md-12 col-sm-12 position-relative hideDiv mb-2" id="modeOfPaymentDiv">

    <div class="card m-0" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
        <div class="card-header justify-content-between">
            <h3>Mode of Payments</h3>
        </div>

        <div class="card-body">

            <div class="row custom-options-checkable mb-2 g-1">
                <div class="col-md-4">
                    <input class="custom-option-item-check checkClass mode-of-payment" type="radio" checked
                        name="mode_of_payment" id="customOptionsCheckableRadiosWithIcon1" value="Cash">
                    <label class="custom-option-item text-center p-1" for="customOptionsCheckableRadiosWithIcon1">
                        {{-- <i data-feather='dollar-sign'></i> --}}
                        <i class="bi bi-cash-coin" style="font-size: 20px"></i>
                        <span class="custom-option-item-title h4 d-block">Cash</span>
                    </label>
                </div>
                <div class="col-md-4">
                    <input class="custom-option-item-check checkClass cheque-mode-of-payment" type="radio"
                        name="mode_of_payment" id="customOptionsCheckableRadiosWithIcon2" value="Cheque">
                    <label class="custom-option-item text-center p-1" for="customOptionsCheckableRadiosWithIcon2">
                        <i class="bi bi-bank" style="font-size: 20px"></i>
                        <span class="custom-option-item-title h4 d-block">Cheque</span>
                    </label>
                </div>
                <div class="col-md-4">
                    <input class="custom-option-item-check checkClass online-mode-of-payment" type="radio"
                        name="mode_of_payment" id="customOptionsCheckableRadiosWithIcon3" value="Online">
                    <label class="custom-option-item text-center p-1" for="customOptionsCheckableRadiosWithIcon3">
                        <i class="bi bi-app-indicator" style="font-size: 20px"></i>
                        <span class="custom-option-item-title h4 d-block">Online</span>
                    </label>
                </div>
                {{-- <div class="col-md-3">
                    <input class="custom-option-item-check other-mode-of-payment" type="radio"
                        name="mode_of_payment" id="customOptionsCheckableRadiosWithIcon4" value="Other">
                    <label class="custom-option-item text-center text-center p-1"
                        for="customOptionsCheckableRadiosWithIcon4">
                        <i class="bi bi-wallet" style="font-size: 20px"></i>
                        <span class="custom-option-item-title h4 d-block">Other</span>
                    </label>
                </div> --}}
            </div>

            <div class="row mb-2 g-1" id="otherValueDiv" style="display: none;">
                <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                    <label class="form-label" style="font-size: 15px" for="other_value">Other Payment
                        Mode <span class="text-danger">*</span></label>
                    <input type="text"
                        class="form-control form-control-lg @error('other_value') is-invalid @enderror"
                        id="other_value" name="other_value" placeholder="Other Payment Mode"
                        value="{{ isset($receipt) ? $receipt->other_value : old('other_value') }}" />
                    @error('other_value')
                        <div class="invalid-tooltip">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-2 g-1" id="onlineValueDiv" style="display: none;">

                <div class="col-lg-6 col-md-6 col-sm-12 position-relative">
                    <label class="form-label" style="font-size: 15px" for="online_instrument_no">Transaction
                        No <span class="text-danger">*</span></label>
                    <input type="text"
                        class="form-control form-control-lg @error('online_instrument_no') is-invalid @enderror"
                        id="online_instrument_no" name="online_instrument_no" placeholder="Online Transaction"
                        value="{{ isset($receipt) ? $receipt->online_instrument_no : old('online_instrument_no') }}" />
                    @error('online_instrument_no')
                        <div class="invalid-tooltip">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-lg-6 col-md-6 col-sm-12 position-relative">
                    <label class="form-label" style="font-size: 15px" for="transaction_date">Transaction
                        Date <span class="text-danger">*</span></label>
                    <input type="date"
                        class="form-control form-control-lg @error('transaction_date') is-invalid @enderror"
                        id="transaction_date" name="transaction_date" placeholder="Transaction Date"
                        value="{{ isset($receipt) ? $receipt->transaction_date : old('transaction_date') }}" />
                    @error('transaction_date')
                        <div class="invalid-tooltip">{{ $message }}</div>
                    @enderror
                </div>
            </div>


            <div class="row mb-2 g-1" id="chequeValueDiv" style="display: none;">

                <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                    <label class="form-label" style="font-size: 15px" for="cheque_no">Cheque No <span
                            class="text-danger">*</span></label>
                    <input type="text"
                        class="form-control form-control-lg @error('cheque_no') is-invalid @enderror" id="cheque_no"
                        name="cheque_no" placeholder="Cheque No"
                        value="{{ isset($receipt) ? $receipt->cheque_no : old('cheque_no') }}" />
                    @error('cheque_no')
                        <span class="text-danger">*</span>
                        <div class="invalid-tooltip">{{ $message }}</div>
                    @enderror
                </div>


            </div>

        </div>
    </div>
</div>

<div class="row mb-2 bankDiv">
    <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
        <div class="card m-0" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
            <div class="card-header justify-content-between">
                <h3>Banks</h3>
            </div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                        <label class="form-label" style="font-size: 15px" for="bank">Select Bank<span
                                class="text-danger">*</span></label>
                        <select class="form-select form-select-lg bank" id="bank" name="bank_id">
                            <option value="0">Create new Bank</option>
                            @foreach ($chequebanks as $banks)
                                <option value="{{ $banks->id }}">{{ $banks->name }} -
                                    {{ $banks->branch_code }}</option>
                                {{-- @empty --}}
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-6 mt-2 position-relative">
                        <div id="div_new_bank">
                            <div class="row mb-1">
                                <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                    <label class="form-label fs-5" for="full_name">Bank Name<span
                                            class="text-danger">*</span></label>
                                    <input type="text"
                                        class="form-control form-control-lg name @error('full_name') is-invalid @enderror"
                                        id="name" name="bank_name" placeholder="Bank Name" />
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                    <label class="form-label fs-5" for="father_name">Account Number<span
                                            class="text-danger">*</span></label>
                                    <input type="number"
                                        class="form-control form-control-lg  account_number @error('account_number') is-invalid @enderror"
                                        id="account_number" name="bank_account_number"
                                        placeholder="Account Number" />
                                    @error('account_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                    <label class="form-label fs-5" for="father_name">Contact Number<span
                                            class="text-danger">*</span></label>
                                    <input type="number"
                                        class="form-control contact_number form-control-lg @error('contact_number') is-invalid @enderror"
                                        id="contact_number" name="bank_contact_number"
                                        placeholder="Contact Number" />
                                    @error('contact_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>

                            <div class="row mb-1">

                                <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                    <label class="form-label fs-5" for="designation">Branch<span
                                            class="text-danger">*</span></label>
                                    <input type="text"
                                        class="form-control branch form-control-lg @error('branch') is-invalid @enderror"
                                        id="branch" name="bank_branch" placeholder="Branch" />
                                    @error('branch')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                    <label class="form-label fs-5" for="contact">Branch Code<span
                                            class="text-danger">*</span></label>
                                    <input type="number"
                                        class="form-control branch_code form-control-lg @error('contact') is-invalid @enderror"
                                        id="branch_code" name="bank_branch_code" placeholder="Branch Code" />
                                    @error('branch_code')
                                        <div class="invalid-feedback ">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-1">
                                <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                    <label class="form-label fs-5" for="address">Address<span
                                            class="text-danger">*</span></label>
                                    <textarea class="form-control address @error('address') is-invalid @enderror" name="bank_address" id="address"
                                        rows="3" placeholder="Address"></textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                    <label class="form-label fs-5" for="comments">Comments</label>
                                    <textarea class="form-control comments @error('comments') is-invalid @enderror" name="bank_comments" id="comments"
                                        rows="3" placeholder="Comments"></textarea>
                                    @error('comments')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="card hideDiv" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;"
    id="installments_acard">
    <div class="card-header">
        <h3>Deal Type</h3>
    </div>

    <div class="card-body">
        <div class="row g-1">
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3">
                <div class="form-check form-check-inline">
                    <label class="form-check-label" for="ideal-deal-check">Idea Deal</label>
                    <input class="form-check-input" checked type="radio" id="ideal-deal-check" name="deal_type"
                        value="ideal-deal"
                        {{ isset($rebate_data) && $rebate_data->deal_type == 'ideal-deal' ? 'checked' : '' }}>
                </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="mark-down-check" name="deal_type"
                        value="mark_down"
                        {{ isset($rebate_data) && $rebate_data->deal_type == 'mark_down' ? 'checked' : '' }}>
                    <label class="form-check-label" for="mark-down-check">Mark Down</label>
                </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="special-case-check" name="deal_type"
                        value="special_case"
                        {{ isset($rebate_data) && $rebate_data->deal_type == 'special_case' ? 'checked' : '' }}>
                    <label class="form-check-label" for="special-case-check">Special Case</label>
                </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="adjustment-check" name="deal_type"
                        value="adjustment"
                        {{ isset($rebate_data) && $rebate_data->deal_type == 'adjustment' ? 'checked' : '' }}>
                    <label class="form-check-label" for="adjustment-check">Adjustment</label>
                </div>
            </div>
        </div>
    </div>
</div>

@if (isset($customFields) && count($customFields) > 0)

    <div class="card hideDiv" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">

        <div class="card-body">

            <div class="row mb-1 g-1">
                @forelse ($customFields as $field)
                    {!! $field !!}
                @empty
                @endforelse
            </div>
        </div>
    </div>
@endif

<div id="comments" class="col-lg-12 col-md-12 col-sm-12 position-relative hideDiv">
    <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
        <div class="card-header justify-content-between">
            <h3>Comments </h3>
        </div>

        <div class="card-body">

            <div class="row mb-1">
                <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                        <textarea class="form-control form-control-lg" id="custom_comments" name="comments" placeholder="Comments"
                            rows="5">{{ isset($rebate_data) ? $rebate_data->comments : old('comments') }}</textarea>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

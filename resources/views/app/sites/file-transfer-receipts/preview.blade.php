@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.file-transfer-receipts.show', encryptParams($site->id)) }}
@endsection

@section('page-title', 'Transfer Receipt Details')

@section('page-vendor')
@endsection

@section('page-css')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/vendors/filepond/filepond.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.preview.min.css">
@endsection


@section('custom-css')
    <style>
        .filepond--drop-label {
            color: #7367F0 !important;
        }

        .filepond--item-panel {
            background-color: #7367F0;
        }

        .filepond--panel-root {
            background-color: #e3e0fd;
        }

        /* .filepond--item {
                                                                                                    width: calc(20% - 0.5em);
                                                                                                } */
    </style>
@endsection

@section('breadcrumbs')
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-start mb-0">Transfer Receipt Details</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('sites.file-transfer-receipts.show', encryptParams($site->id)) }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 position-relative">

            <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                <div class="card-header">
                    <h3>2. UNIT DATA</h3>
                </div>

                <div class="card-body">
                    <div class="row mb-1">
                        <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                            <label class="form-label fs-5" for="unit_no">Unit Name</label>
                            <input type="text" class="form-control form-control-lg" id="unit_no" name="unit[no]"
                                placeholder="Unit No" value="{{ $unit_data->name }}" readonly />
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                            <label class="form-label fs-5" for="floor_no">Floor No</label>
                            <input type="text" class="form-control form-control-lg" id="floor_no" name="unit[floor_no]"
                                placeholder="Floor No" value="{{ $unit_data->floor_unit_number }}" readonly />
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                            <label class="form-label fs-5" for="unit_type">Unit Type</label>
                            <input type="text" class="form-control form-control-lg" id="unit_type" name="unit[type]"
                                placeholder="Unit Type" value="{{ $unit_data->type->name }}" readonly />
                        </div>

                    </div>

                    <div class="row mb-1">
                        <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                            <label class="form-label fs-5" for="unit_no">Unit Area(sq.ft)</label>
                            <input type="text" class="form-control form-control-lg" id="unit_no" name="unit[no]"
                                placeholder="Unit No" value="{{ number_format($unit_data->gross_area) }}" readonly />
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                            <label class="form-label fs-5" for="floor_no">Unit Price</label>
                            <input type="text" class="form-control form-control-lg" id="floor_no" name="unit[floor_no]"
                                placeholder="Floor No" value="{{ number_format($sales_plan->unit_price) }}" readonly />
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                            <label class="form-label fs-5" for="unit_type">Total Price</label>
                            <input type="text" class="form-control form-control-lg" id="unit_type" name="unit[type]"
                                placeholder="Unit Type" value="{{ number_format($sales_plan->total_price) }}" readonly />
                        </div>

                    </div>

                </div>
            </div>

            <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                <div class="card-header">
                    <h3>3. RECEIPT DATA</h3>
                </div>

                <div class="card-body">
                    <div class="row mb-1">

                        <div class="col-lg-9 col-md-9 col-sm-12">
                            <div class="row">

                                <div class="col-lg-6 col-md-6 col-sm-12 mb-2 position-relative">
                                    <label class="form-label fs-5" for="unit_no">Total Amount</label>
                                    <input type="text" class="form-control form-control-lg" id="unit_no"
                                        name="unit[no]" placeholder="" value="{{ number_format($receipt->amount) }}"
                                        readonly />
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-12 mb-2 position-relative">
                                    <label class="form-label fs-5" for="floor_no">Amount In Words</label>
                                    <input type="text" class="form-control form-control-lg" id="floor_no"
                                        name="unit[floor_no]" placeholder=""
                                        value="{{ \Str::title(numberToWords($receipt->amount)) }} Only." readonly />
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-12 mb-2 position-relative">
                                    <label class="form-label fs-5" for="unit_type">Mode Of Payment</label>
                                    <input type="text" class="form-control form-control-lg" id="unit_type"
                                        name="unit[type]" placeholder="Unit Type"
                                        value="{{ $receipt->mode_of_payment }}" readonly />
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-12 mb-2 position-relative">
                                    <label class="form-label fs-5" for="unit_type">Created At</label>
                                    <input type="text" class="form-control form-control-lg" id="unit_type"
                                        name="unit[type]" placeholder="Unit Type"
                                        value="{{ \Carbon\Carbon::parse($receipt->created_date)->format('F j, Y') }}"
                                        readonly />
                                </div>
                                @if ($receipt->mode_of_payment == 'Cheque')
                                    <div class="col-lg-6 col-md-6 col-sm-12 mb-2 position-relative">
                                        <label class="form-label fs-5" for="unit_type">Check Number</label>
                                        <input type="text" class="form-control form-control-lg" id="unit_type"
                                            name="unit[type]" placeholder="Check Number"
                                            value="{{ $receipt->cheque_no }}" readonly />
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-12 mb-2 position-relative">
                                        <label class="form-label fs-5" for="unit_type">Bank Name</label>
                                        <input type="text" class="form-control form-control-lg" id="unit_type"
                                            name="unit[type]" placeholder="Bank Name"
                                            value="{{ $receipt->bank_details }}" readonly />
                                    </div>
                                @endif

                                @if ($receipt->mode_of_payment == 'Online')
                                    <div class="col-lg-6 col-md-6 col-sm-12 mb-2 position-relative">
                                        <label class="form-label fs-5" for="unit_type">Transaction No</label>
                                        <input type="text" class="form-control form-control-lg" id="unit_type"
                                            name="unit[type]" placeholder="Transaction No"
                                            value="{{ $receipt->online_transaction_no }}" readonly />
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-12 mb-2 position-relative">
                                        <label class="form-label fs-5" for="unit_type">Transaction Date</label>
                                        <input type="text" class="form-control form-control-lg" id="unit_type"
                                            name="unit[type]" placeholder="Transaction Date"
                                            value="{{ $receipt->transaction_date }}" readonly />
                                    </div>
                                @endif

                                @if ($receipt->mode_of_payment == 'Other')
                                    <div class="col-lg-6 col-md-6 col-sm-12 mb-2 position-relative">
                                        <label class="form-label fs-5" for="unit_type">Other Payment Mode</label>
                                        <input type="text" class="form-control form-control-lg" id="unit_type"
                                            name="unit[type]" placeholder="Other Payment Mode"
                                            value="{{ $receipt->other_value }}" readonly />
                                    </div>
                                @endif

                                <div class="col-lg-12 col-md-12 col-sm-12 position-relative mt-1">
                                    <label class="form-label fs-5" for="stackholder_address">Comments</label>
                                    <textarea class="form-control  form-control-lg" readonly id="stackholder_address" placeholder="Address"
                                        rows="2">{{ $receipt->comments }}</textarea>
                                </div>

                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-ms-12">
                            <label class="form-label fs-5" for="type_name">Attachment</label>
                            <input disabled id="attachment" type="file"
                                class="filepond @error('attachment') is-invalid @enderror" name="attachment"
                                accept="image/png, image/jpeg, image/gif" />
                            @error('attachment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                </div>
            </div>

            <div id="transferOwner" class="col-lg-12 col-md-12 col-sm-12 position-relative">
                <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;"
                    id="stakeholders_card">
                    <div class="card-header justify-content-between">
                        <h3> Transfer Owner Information</h3>
                    </div>

                    <div class="card-body">

                        {{--  individual Form --}}
                        @if ($transferOwner->stakeholder_as == 'i')
                            <div id="individualForm">
                                <div class="row mb-1">
                                    <div class="col-lg-4 col-md-4 col-sm-12 position-relative">
                                        <label class="form-label fs-5" for="transferOwner_full_name">Full Name <span
                                                class="text-danger">*</span></label>
                                        <input readonly type="text" class="form-control form-control-lg"
                                            id="transferOwner_full_name" placeholder="Full Name"
                                            value="{{ $transferOwner->full_name }}" />
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-12 position-relative">
                                        <label class="form-label fs-5" for="transferOwner_father_name">Father / Husband
                                            Name
                                            <span class="text-danger">*</span></label>
                                        <input readonly type="text" class="form-control form-control-lg"
                                            id="transferOwner_father_name" placeholder="Father / Husband Name"
                                            value="{{ $transferOwner->father_name }}" />
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-12 position-relative">
                                        <label class="form-label fs-5" for="transferOwner_occupation">Occupation </label>
                                        <input readonly type="text" class="form-control form-control-lg"
                                            id="transferOwner_occupation" placeholder="Occupation"
                                            value="{{ $transferOwner->occupation }}" />
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-lg-4 col-md-4 col-sm-12 position-relative">
                                        <label class="form-label fs-5" for="transferOwner_designation">Designation</label>
                                        <input readonly type="text" class="form-control form-control-lg"
                                            id="transferOwner_designation" placeholder="Designation"
                                            value="{{ $transferOwner->designation }}" />
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-12 position-relative">
                                        <label class="form-label fs-5" for="transferOwner_ntn">NTN </label>
                                        <input readonly type="text" class="form-control form-control-lg"
                                            id="transferOwner_ntn" placeholder="NTN"
                                            value="{{ $transferOwner->ntn }}" />
                                    </div>

                                    <div class="col-lg-4 col-md-4 col-sm-12 position-relative">
                                        <label class="form-label fs-5" for="transferOwner_cnic">CNIC <span
                                                class="text-danger">*</span></label>
                                        <input readonly type="text" class="form-control form-control-lg"
                                            id="transferOwner_cnic" placeholder="CNIC"
                                            value="{{ $transferOwner->cnic }}" />
                                    </div>
                                </div>
                            </div>
                        @endif
                        {{-- company form --}}
                        @if ($transferOwner->stakeholder_as == 'c')
                            <div id="companyForm">
                                <div class="row mb-1">
                                    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                        <label class="form-label fs-5" for="company_name">Company Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" readonly class="form-control form-control-lg"
                                            id="transferOwner_company_name" placeholder="Company Name"
                                            value="{{ $transferOwner->full_name }}" />
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                        <label class="form-label fs-5" for="industry">Industry </label>
                                        <input type="text" readonly class="form-control form-control-lg"
                                            id="transferOwner_industry" placeholder="Industry"
                                            value="{{ $transferOwner->industry }}" />
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                        <label class="form-label fs-5" for="registration">Registration # <span
                                                class="text-danger">*</span></label>
                                        <input type="text" readonly class="cp_cnic form-control form-control-lg"
                                            id="transferOwner_registration" placeholder="Registration Number"
                                            value="{{ $transferOwner->cnic }}" />

                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                        <label class="form-label fs-5" for="ntn">NTN </label>
                                        <input type="number" readonly
                                            class="form-control form-control-lg @error('ntn') is-invalid @enderror"
                                            id="transferOwner_ntn" placeholder="NTN Number"
                                            value="{{ $transferOwner->ntn }}" />

                                    </div>
                                </div>
                            </div>
                        @endif
                        {{-- common form  --}}
                        <div class="row mb-1">
                            <div class="col-lg-3 col-md-3 col-sm-12 position-relative">
                                <label class="form-label fs-5" for="transferOwner_email">Email <span
                                        class="text-danger">*</span></label>
                                <input type="email" readonly class="form-control form-control-md"
                                    id="transferOwner_email" placeholder="Email" autocomplete="false"
                                    value="{{$transferOwner->email}}" />

                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-12 position-relative">
                                <label class="form-label fs-5" for="transferOwner_optional_email">Optional Email</label>
                                <input type="email" readonly class="form-control form-control-md"
                                    id="transferOwner_optional_email" placeholder="Optional Email" autocomplete="false"
                                    value="{{$transferOwner->office_email}}" />
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-12 position-relative">
                                <label class="form-label fs-5" for="transferOwner_contact">Contact <span
                                        class="text-danger">*</span></label>
                                <input readonly type="text" class="form-control form-control-lg"
                                    id="transferOwner_contact" placeholder="Contact"
                                    value="{{$transferOwner->mobile_contact}}" />
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12">
                                <label class="form-label fs-5" for="transferOwner_optional_contact">Optional Contact <span
                                        class="text-danger">*</span></label>
                                <input readonly type="text" class="form-control form-control-lg"
                                    id="transferOwner_optional_contact" placeholder="Optional Contact"
                                    value="{{$transferOwner->office_contact}}" />
                            </div>
                        </div>

                        <div class="row mb-1">
                            <div class="col-lg-3 col-md-3 col-sm-6 position-relative">
                                <label class="form-label" style="font-size: 15px" for="parent_id">Country</label>
                                <input readonly type="text" class="form-control form-control-lg"
                                    id="transferOwner_country" placeholder="Country"
                                    value="{{$transferOwner->residentialCountry->name ?? ''}}" />

                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-6 position-relative">
                                <label class="form-label" style="font-size: 15px" for="city_id">State</label>
                                <input readonly type="text" class="form-control form-control-lg"
                                    id="transferOwner_state" placeholder="State" value="{{$transferOwner->residentialState->name ?? ''}}" />

                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-6 position-relative">
                                <label class="form-label" style="font-size: 15px" for="city_id">City</label>
                                <input readonly type="text" class="form-control form-control-lg"
                                    id="transferOwner_city" placeholder="City" value="{{$transferOwner->residentialCity->name ?? ''}}" />

                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 position-relative">
                                <label class="form-label fs-5" for="transferOwner_nationality">Nationality </label>
                                <input type="text" readonly
                                    class="form-control form-control-lg @error('occupation') is-invalid @enderror"
                                    id="transferOwner_nationality" placeholder="Nationality"
                                    value="{{ $transferOwner->nationalityCountry->name ?? '' }}" />

                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-lg-6 col-md-6 col-sm-12 position-relative">
                                <label class="form-label fs-5" for="transferOwner_address">Address <span
                                        class="text-danger">*</span></label>
                                <textarea readonly class="form-control form-control-lg" id="transferOwner_address" placeholder="Address"
                                    rows="3"> {{ $transferOwner->residential_address }}</textarea>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 position-relative">
                                <label class="form-label fs-5" for="transferOwner_mailing_address">Mailing Address <span
                                        class="text-danger">*</span>
                                </label>
                                <textarea readonly class="form-control form-control-lg" id="transferOwner_mailing_address"
                                    placeholder="Mailing Address" rows="3">{{ $transferOwner->mailing_address }}</textarea>
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-lg- col-md- col-sm-12 position-relative">
                                <label class="form-label fs-5" for="transferOwner_comments">Comments</label>
                                <textarea readonly class="form-control form-control-lg" id="transferOwner_comments" placeholder="Comments"
                                    rows="3">{{ $transferOwner->comments }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="fileOwner" class="col-lg-12 col-md-12 col-sm-12 position-relative">
                <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;"
                    id="stakeholders_card">
                    <div class="card-header justify-content-between">
                        <h3> File Owner Information</h3>
                    </div>

                    <div class="card-body">

                        {{--  individual Form --}}
                        @if ($fileOwner->stakeholder_as == 'i')
                            <div id="OwnerIndividualForm">
                                <div class="row mb-1">
                                    <div class="col-lg-4 col-md-4 col-sm-12 position-relative">
                                        <label class="form-label fs-5" for="fileOwner_full_name">Full Name <span
                                                class="text-danger">*</span></label>
                                        <input readonly type="text" class="form-control form-control-lg"
                                            id="fileOwner_full_name" placeholder="Full Name"
                                            value="{{ $fileOwner->full_name }}" />
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-12 position-relative">
                                        <label class="form-label fs-5" for="fileOwner_father_name">Father / Husband Name
                                            <span class="text-danger">*</span></label>
                                        <input readonly type="text" class="form-control form-control-lg"
                                            id="fileOwner_father_name" placeholder="Father / Husband Name"
                                            value="{{ $fileOwner->father_name }}" />
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-12 position-relative">
                                        <label class="form-label fs-5" for="fileOwner_occupation">Occupation </label>
                                        <input readonly type="text" class="form-control form-control-lg"
                                            id="fileOwner_occupation" placeholder="Occupation"
                                            value="{{ $fileOwner->occupation }}" />
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-lg-4 col-md-4 col-sm-12 position-relative">
                                        <label class="form-label fs-5" for="fileOwner_designation">Designation</label>
                                        <input readonly type="text" class="form-control form-control-lg"
                                            id="fileOwner_designation" placeholder="Designation"
                                            value="{{ $fileOwner->designation }}" />
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-12 position-relative">
                                        <label class="form-label fs-5" for="fileOwner_ntn">NTN </label>
                                        <input readonly type="text" class="form-control form-control-lg"
                                            id="fileOwner_ntn" placeholder="NTN" value="{{ $fileOwner->ntn }}" />
                                    </div>

                                    <div class="col-lg-4 col-md-4 col-sm-12 position-relative">
                                        <label class="form-label fs-5" for="fileOwner_cnic">CNIC <span
                                                class="text-danger">*</span></label>
                                        <input readonly type="text" class="form-control form-control-lg"
                                            id="fileOwner_cnic" placeholder="CNIC" value="{{ $fileOwner->cnic }}" />
                                    </div>
                                </div>
                            </div>
                        @endif
                        {{-- company form --}}
                        @if ($fileOwner->stakeholder_as == 'c')
                            <div id="OwnerCompanyForm">
                                <div class="row mb-1">
                                    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                        <label class="form-label fs-5" for="company_name">Company Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" readonly class="form-control form-control-lg"
                                            id="fileOwner_company_name" placeholder="Company Name"
                                            value="{{ $fileOwner->full_name }}" />
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                        <label class="form-label fs-5" for="industry">Industry </label>
                                        <input type="text" readonly class="form-control form-control-lg"
                                            id="fileOwner_industry" placeholder="Industry"
                                            value="{{ $fileOwner->industry }}" />
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                        <label class="form-label fs-5" for="registration">Registration # <span
                                                class="text-danger">*</span></label>
                                        <input type="text" readonly class="cp_cnic form-control form-control-lg"
                                            id="fileOwner_registration" placeholder="Registration Number"
                                            value="{{ $fileOwner->cnic }}" />
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                        <label class="form-label fs-5" for="ntn">NTN </label>
                                        <input type="number" readonly
                                            class="form-control form-control-lg @error('ntn') is-invalid @enderror"
                                            id="fileOwner_ntn" placeholder="NTN Number" value="{{ $fileOwner->ntn }}" />
                                    </div>
                                </div>
                            </div>
                        @endif
                        {{-- common form  --}}
                        <div class="row mb-1">
                            <div class="col-lg-3 col-md-3 col-sm-12 position-relative">
                                <label class="form-label fs-5" for="fileOwner_email">Email <span
                                        class="text-danger">*</span></label>
                                <input type="email" readonly class="form-control form-control-md" id="fileOwner_email"
                                    placeholder="Email" autocomplete="false" value="{{ $fileOwner->email }}" />

                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-12 position-relative">
                                <label class="form-label fs-5" for="fileOwner_optional_email">Optional Email</label>
                                <input type="email" readonly class="form-control form-control-md"
                                    id="fileOwner_optional_email" placeholder="Optional Email" autocomplete="false"
                                    value="{{ $fileOwner->office_email }}" />
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-12 position-relative">
                                <label class="form-label fs-5" for="fileOwner_contact">Contact <span
                                        class="text-danger">*</span></label>
                                <input readonly type="text" class="form-control form-control-lg"
                                    id="fileOwner_contact" placeholder="Contact" value="{{ $fileOwner->mobile_contact }}" />
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12">
                                <label class="form-label fs-5" for="fileOwner_optional_contact">Optional Contact <span
                                        class="text-danger">*</span></label>
                                <input readonly type="text" class="form-control form-control-lg"
                                    id="fileOwner_optional_contact" placeholder="Optional Contact"
                                    value="{{ $fileOwner->office_contact }}" />
                            </div>
                        </div>

                        <div class="row mb-1">
                            <div class="col-lg-3 col-md-3 col-sm-6 position-relative">
                                <label class="form-label" style="font-size: 15px" for="parent_id">Country</label>
                                <input readonly type="text" class="form-control form-control-lg"
                                    id="fileOwner_country" placeholder="Country" value="{{ $fileOwner->residentialCountry->name ?? '' }}" />

                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-6 position-relative">
                                <label class="form-label" style="font-size: 15px" for="city_id">State</label>
                                <input readonly type="text" class="form-control form-control-lg" id="fileOwner_state"
                                    placeholder="State" value="{{ $fileOwner->residentialState->name ?? ''}}" />

                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-6 position-relative">
                                <label class="form-label" style="font-size: 15px" for="city_id">City</label>
                                <input readonly type="text" class="form-control form-control-lg" id="fileOwner_city"
                                    placeholder="City" value="{{ $fileOwner->residentialCity->name ?? ''}}" />

                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 position-relative">
                                <label class="form-label fs-5" for="fileOwner_nationality">Nationality </label>
                                <input type="text" readonly
                                    class="form-control form-control-lg @error('occupation') is-invalid @enderror"
                                    id="fileOwner_nationality" placeholder="Nationality"
                                    value="{{ $fileOwner->nationalityCountry->name }}" />

                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-lg-6 col-md-6 col-sm-12 position-relative">
                                <label class="form-label fs-5" for="fileOwner_address">Address <span
                                        class="text-danger">*</span></label>
                                <textarea readonly class="form-control form-control-lg" id="fileOwner_address" placeholder="Address" rows="3"> {{ $fileOwner->residential_address }}</textarea>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 position-relative">
                                <label class="form-label fs-5" for="fileOwner_mailing_address">Mailing Address <span
                                        class="text-danger">*</span>
                                </label>
                                <textarea readonly class="form-control form-control-lg" id="fileOwner_mailing_address" placeholder="Mailing Address"
                                    rows="3">{{ $fileOwner->mailing_address }}</textarea>
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-lg- col-md- col-sm-12 position-relative">
                                <label class="form-label fs-5" for="fileOwner_comments">Comments</label>
                                <textarea readonly class="form-control form-control-lg" id="fileOwner_comments" placeholder="Comments"
                                    rows="3">{{ $fileOwner->comments }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection

@section('vendor-js')
    <script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.preview.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.typevalidation.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.imagecrop.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.imagesizevalidation.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.filesizevalidation.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/filepond/filepond.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/forms/repeater/jquery.repeater.min.js"></script>
    <script src="{{ asset('app-assets') }}/js/scripts/forms/form-repeater.min.js"></script>
@endsection

@section('page-js')
@endsection

@section('custom-js')
    <script>
        FilePond.registerPlugin(
            FilePondPluginImagePreview,
            FilePondPluginFileValidateType,
            FilePondPluginFileValidateSize,
            FilePondPluginImageValidateSize,
            FilePondPluginImageCrop,
        );

        var files = [];
        @if ($image != '')
            files.push({
                source: '{{ $image }}',
            });
        @endif

        FilePond.create(document.getElementById('attachment'), {
            files: files,
            styleButtonRemoveItemPosition: 'right',
            imageCropAspectRatio: '1:1',
            acceptedFileTypes: ['image/png', 'image/jpeg'],
            maxFileSize: '1536KB',
            ignoredFiles: ['.ds_store', 'thumbs.db', 'desktop.ini'],
            storeAsFile: true,
            allowMultiple: true,
            maxFiles: 2,
            minFiles: 2,
            checkValidity: true,
            credits: {
                label: '',
                url: ''
            }
        });
    </script>
@endsection

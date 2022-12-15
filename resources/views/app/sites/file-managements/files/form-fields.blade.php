<div class="bs-stepper wizard-modern modern-wizard-example">

    <div class="bs-stepper-header">

        <div class="step" data-target="#applicaiton-form" role="tab" id="applicaiton-form-trigger">
            <button type="button" class="step-trigger">
                <span class="bs-stepper-box waves-effect waves-float waves-light" class="font-medium-3">1</span>
                <span class="bs-stepper-label">
                    <span class="bs-stepper-title">Application Form</span>
                    <span class="bs-stepper-subtitle">Validate Application Form</span>
                </span>
            </button>
        </div>

        <div class="line">
            <i data-feather="chevron-right" class="font-medium-2"></i>
        </div>

        <div class="step" data-target="#sales-agreement" role="tab" id="sales-agreement-trigger">
            <button type="button" class="step-trigger">
                <span class="bs-stepper-box waves-effect waves-float waves-light" class="font-medium-3">2</span>
                <span class="bs-stepper-label">
                    <span class="bs-stepper-title">Sales Agreement</span>
                    <span class="bs-stepper-subtitle">Validate Sales Agreement</span>
                </span>
            </button>
        </div>

        <div class="line">
            <i data-feather="chevron-right" class="font-medium-2"></i>
        </div>

        <div class="step" data-target="#sales-plan" role="tab" id="sales-plan-trigger">
            <button type="button" class="step-trigger">
                <span class="bs-stepper-box waves-effect waves-float waves-light" class="font-medium-3">3</span>
                <span class="bs-stepper-label">
                    <span class="bs-stepper-title">Sales Plan</span>
                    <span class="bs-stepper-subtitle">Validate Sales Plan</span>
                </span>
            </button>
        </div>

        <div class="line">
            <i data-feather="chevron-right" class="font-medium-2"></i>
        </div>

        <div class="step" data-target="#receipts" role="tab" id="receipts-trigger">
            <button type="button" class="step-trigger">
                <span class="bs-stepper-box waves-effect waves-float waves-light" class="font-medium-3">4</span>
                <span class="bs-stepper-label">
                    <span class="bs-stepper-title">Receipts</span>
                    <span class="bs-stepper-subtitle">Validate Receipts</span>
                </span>
            </button>
        </div>

        <div class="line">
            <i data-feather="chevron-right" class="font-medium-2"></i>
        </div>

        <div class="step" data-target="#booking-form" role="tab" id="booking-form-trigger">
            <button type="button" class="step-trigger">
                <span class="bs-stepper-box waves-effect waves-float waves-light" class="font-medium-3">5</span>
                <span class="bs-stepper-label">
                    <span class="bs-stepper-title">Booking Form</span>
                    <span class="bs-stepper-subtitle">Validate Booking Form</span>
                </span>
            </button>
        </div>

        <div class="line">
            {{-- <i data-feather="chevron-right" class="font-medium-2"></i> --}}
        </div>

        {{-- <div class="step" data-target="#rebate-form" role="tab" id="rebate-form-trigger">
            <button type="button" class="step-trigger">
                <span class="bs-stepper-box waves-effect waves-float waves-light" class="font-medium-3">6</span>
                <span class="bs-stepper-label">
                    <span class="bs-stepper-title">Rebate Form</span>
                    <span class="bs-stepper-subtitle">Validate Rebate Form</span>
                </span>
            </button>
        </div> --}}
    </div>

    <div class="bs-stepper-content p-0">

        <div class="card content shadow-none m-0" id="applicaiton-form" role="tabpanel"
            aria-labelledby="applicaiton-form-trigger">
            <div class="card-body">
                <div class="row g-1 mb-2">
                    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                        <label class="form-label fs-5" for="registration_no">Registration No <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-lg" id="registration_no"
                            @isset($customer_file)
                             value="{{ $customer_file->registration_no }}" readonly
                            @endisset
                            name="application_form[registration_no]" placeholder="Registration No" />
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                        <label class="form-label fs-5" for="application_no">Application No <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-lg" id="application_no"
                            @isset($customer_file)
                             value="{{ $customer_file->application_no }}" readonly
                            @endisset
                            name="application_form[application_no]" placeholder="Application No" />
                    </div>
                </div>

                <div class="row mb-1">
                    <div class="col-lg-10 col-md-10 col-sm-12">
                        {{-- Units Data --}}
                        <div class="row mb-1">
                            <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                                <div class="card m-0"
                                    style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                                    <div class="card-header">
                                        <h3>Units</h3>
                                    </div>

                                    <div class="card-body">
                                        <div class="row g-1 mb-1">
                                            <input type="hidden" name="application_form[unit_id]"
                                                value="{{ $unit->id }}">
                                            <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                                <label class="form-label fs-5" for="unit_no">Unit No</label>
                                                <input type="text" class="form-control form-control-lg"
                                                    id="unit_no" placeholder="Unit No"
                                                    value="{{ $unit->floor_unit_number }}" disabled />
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                                <label class="form-label fs-5" for="unit_type">Unit Type</label>
                                                <input type="text" class="form-control form-control-lg"
                                                    id="unit_type" value="{{ $unit->type->name }}"
                                                    placeholder="Unit Type" disabled />
                                            </div>
                                        </div>

                                        <div class="row g-1">
                                            <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                                <label class="form-label fs-5" for="unit_size">Size</label>
                                                <input type="text" class="form-control form-control-lg"
                                                    id="unit_size" placeholder="Size"
                                                    value="{{ $unit->gross_area }} sqft" disabled />
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                                <label class="form-label fs-5" for="unit_floor">Floor</label>
                                                <input type="text" class="form-control form-control-lg"
                                                    id="unit_floor" placeholder="Floor"
                                                    value="{{ $unit->floor->short_label }}" disabled />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-2 col-sm-12">
                        <label class="form-label fs-5" for="application_photo">Photo <span
                                class="text-danger">*</span></label>
                        <input @if (isset($customer_file)) disabled @endif id="application_photo" type="file"
                            class="filepond" name="application_form[photo]" accept="image/png, image/jpeg" />
                    </div>
                </div>

                {{-- Stakeholder Data --}}
                <div class="row mb-1">
                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                        <div class="card m-0"
                            style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                            <div class="card-header">
                                <h3>Customer</h3>
                            </div>

                            <div class="card-body">

                                <div class="row g-1 mb-1">
                                    <input type="hidden" name="application_form[stakeholder_id]"
                                        value="{{ $customer->id }}">

                                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                        <label class="form-label fs-5" for="customer_name">Name</label>
                                        <input type="text" class="form-control form-control-lg" id="customer_name"
                                            placeholder="Name" value="{{ $customer->full_name }}" disabled />
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                        <label class="form-label fs-5" for="customer_father_name">Father/Husband
                                            Name</label>
                                        <input type="text" class="form-control form-control-lg"
                                            id="customer_father_name" value="{{ $customer->father_name }}"
                                            placeholder="Father/Husband Name" disabled />
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                        <label class="form-label fs-5" for="customer_cnic">CNIC/Passport</label>
                                        <input type="text" class="form-control form-control-lg" id="customer_cnic"
                                            placeholder="CNIC/Passport"
                                            value="{{ cnicFormat($customer->cnic) ?? '-' }}" disabled />
                                    </div>
                                </div>

                                <div class="row g-1 mb-1">
                                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                        <label class="form-label fs-5" for="customer_ntn">NTN Number</label>
                                        <input type="text" class="form-control form-control-lg" id="customer_ntn"
                                            placeholder="NTN Number" value="{{ $customer->ntn ?? '-' }}" disabled />
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                        <label class="form-label fs-5" for="customer_phone">Cell</label>
                                        <input type="text" class="form-control form-control-lg"
                                            id="customer_phone" placeholder="Cell"
                                            value="{{ $customer->contact ?? '-' }}" disabled />
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                        <label class="form-label fs-5" for="customer_occupation">Occupation</label>
                                        <input type="text" class="form-control form-control-lg"
                                            id="customer_occupation" placeholder="Occupation"
                                            value="{{ $customer->occupation ?? '-' }}" disabled />
                                    </div>
                                </div>

                                <div class="row g-1 mb-1">
                                    <div class="col-lg-6 col-md-6 col-sm-12 position-relative">
                                        <label class="form-label fs-5" for="customer_address">Address</label>
                                        <input type="text" class="form-control form-control-lg"
                                            id="customer_address" placeholder="Address"
                                            value="{{ $customer->address ?? '-' }}" disabled />
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-12 position-relative">
                                        <label class="form-label fs-5" for="customer_comments">Comments</label>
                                        <input type="text" class="form-control form-control-lg"
                                            id="customer_comments" placeholder="Comments"
                                            value="{{ $customer->comments ?? '-' }}" disabled />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Next Of KIN Data --}}
                @forelse ($nextOfKin as $kin)
                    <div class="row mb-1">
                        <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                            <div class="card m-0"
                                style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                                <div class="card-header">
                                    <h3>Next Of KIN</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row g-1 mb-1">
                                        <input type="hidden" name="application_form[stakeholder_id]"
                                            value="{{ $kin->id }}">

                                        <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                            <label class="form-label fs-5" for="customer_name">Name</label>
                                            <input type="text" class="form-control form-control-lg"
                                                id="customer_name" placeholder="Name" value="{{ $kin->full_name }}"
                                                disabled />
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                            <label class="form-label fs-5" for="customer_father_name">Father/Husband
                                                Name</label>
                                            <input type="text" class="form-control form-control-lg"
                                                id="customer_father_name" value="{{ $kin->father_name }}"
                                                placeholder="Father/Husband Name" disabled />
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                            <label class="form-label fs-5"
                                                for="customer_relationship">Relationship</label>
                                            <input type="text" class="form-control form-control-lg"
                                                id="customer_relationship" value="{{ $kin->relation }}"
                                                placeholder="Relationship" disabled />
                                        </div>
                                    </div>

                                    <div class="row g-1 mb-1">
                                        <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                            <label class="form-label fs-5" for="customer_cnic">CNIC/Passport</label>
                                            <input type="text" class="form-control form-control-lg"
                                                id="customer_cnic" placeholder="CNIC/Passport"
                                                value="{{ cnicFormat($kin->cnic) }}" disabled />
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                            <label class="form-label fs-5" for="customer_phone">Cell</label>
                                            <input type="text" class="form-control form-control-lg"
                                                id="customer_phone" placeholder="Cell" value="{{ $kin->contact }}"
                                                disabled />
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                            <label class="form-label fs-5"
                                                for="customer_occupation">Occupation</label>
                                            <input type="text" class="form-control form-control-lg"
                                                id="customer_occupation" placeholder="Occupation"
                                                value="{{ $kin->occupation }}" disabled />
                                        </div>
                                    </div>

                                    <div class="row g-1 mb-1">
                                        <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                                            <label class="form-label fs-5" for="customer_address">Address</label>
                                            <input type="text" class="form-control form-control-lg"
                                                id="customer_address" placeholder="Address"
                                                value="{{ $kin->address }}" disabled />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                @endforelse
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-end">

                    <button class="btn btn-relief-outline-primary waves-effect waves-float waves-light btn-next"
                        type="button">
                        <span class="align-middle d-sm-inline-block d-none">Next</span>
                        <i data-feather="arrow-right" class="align-middle ms-sm-25 ms-0"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="card content shadow-none m-0" id="sales-agreement" role="tabpanel"
            aria-labelledby="sales-agreement-trigger">

            <div class="card-body">
                <div>
                    <h1 class="text-bold text-underline text-center p-3"><u><strong>Agreement</strong></u></h1>
                    <div class="row">
                        <div class="col px-3">
                            <p class="text-bold h4"><strong>THIS AGREEMENT TO SELL
                                    ("Agreement")</strong>, executed at Islamabad on the
                                <span class="px-3"><u><strong>22-Aug-2022</strong></u></span><br>
                            </p>
                            <p class="text-bold h4">by and between:</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    <button class="btn btn-relief-outline-primary waves-effect waves-float waves-light btn-prev"
                        type="button">
                        <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                        <span class="align-middle d-sm-inline-block d-none">Previous</span>
                    </button>
                    <button class="btn btn-relief-outline-primary waves-effect waves-float waves-light btn-next"
                        type="button">
                        <span class="align-middle d-sm-inline-block d-none">Next</span>
                        <i data-feather="arrow-right" class="align-middle ms-sm-25 ms-0"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="card content shadow-none m-0" id="sales-plan" role="tabpanel"
            aria-labelledby="sales-plan-trigger">

            <div class="card-body">

                <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                    <div class="card-header">
                        <h3>1. PRIMARY DATA</h3>
                    </div>

                    <div class="card-body">
                        <div class="row mb-1">
                            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                <label class="form-label fs-5" for="unit_no">Unit No</label>
                                <input type="text" class="form-control form-control-lg" id="unit_no"
                                    placeholder="Unit No" value="{{ $unit->floor_unit_number }}" disabled />
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                <label class="form-label fs-5" for="floor_no">Floor No</label>
                                <input type="text" class="form-control form-control-lg" id="floor_no"
                                    placeholder="Floor No" value="{{ $unit->floor->short_label }}" disabled />
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                <label class="form-label fs-5" for="unit_type">Unit Type</label>
                                <input type="text" class="form-control form-control-lg" id="unit_type"
                                    placeholder="Unit Type" value="{{ $unit->type->name }}" disabled />
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                <label class="form-label fs-5" for="unit_size">Unit Size(sq.ft)</label>
                                <input type="text" class="form-control form-control-lg" id="unit_size"
                                    placeholder="Unit Size(sq.ft)" value="{{ $unit->gross_area }}" disabled />
                            </div>
                        </div>

                        {{-- PRICING --}}
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                                <div class="card m-0"
                                    style="border: 2px solid #eee; border-style: dashed; border-radius: 0;">
                                    <div class="card-header">
                                        <h3>PRICING</h3>
                                    </div>

                                    <div class="card-body">
                                        {{-- Unit Rate Row --}}
                                        <div class="row mb-1" id="div-unit">
                                            <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                                <label class="form-label fs-5" for="unit_price">Unit Price
                                                    (Rs)</label>
                                                <input type="text" class="form-control form-control-lg"
                                                    id="unit_price" placeholder="Unit Price"
                                                    value="{{ number_format($salesPlan->unit_price, 2) }}" disabled />
                                            </div>

                                            <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                                <label class="form-label fs-5" for="total-price-unit">Amount
                                                    (Rs)</label>
                                                <input type="text" class="form-control form-control-lg" disabled
                                                    id="total-price-unit" placeholder="Amount"
                                                    value="{{ number_format($salesPlan->unit_price * $unit->gross_area) }}" />
                                            </div>
                                        </div>

                                        <div id="div_additional_cost">

                                            @php
                                                $total_additional_cost = 0;
                                            @endphp

                                            @foreach ($salesPlan->additionalCosts as $key => $additionalCost)
                                                @php
                                                    $total_additional_cost += $additionalCost->pivot->amount;
                                                @endphp

                                                <div class="row mb-1">
                                                    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                                        <label class="form-label fs-5"
                                                            for="price-{{ $additionalCost->slug }}-{{ $key }}">{{ $additionalCost->name }}
                                                            (%)
                                                        </label>

                                                        <input type="number" class="form-control form-control-lg"
                                                            id="percentage-{{ $additionalCost->slug }}-{{ $key }}"
                                                            placeholder="{{ $additionalCost->name }}" disabled
                                                            value="{{ $additionalCost->pivot->percentage }}" />

                                                    </div>

                                                    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                                        <label class="form-label fs-5"
                                                            for="total-price-{{ $additionalCost->slug }}-{{ $key }}">Amount
                                                            (Rs)</label>

                                                        <input type="text" class="form-control form-control-lg"
                                                            id="total-price-{{ $additionalCost->slug }}-{{ $key }}"
                                                            disabled placeholder="Amount"
                                                            value="{{ number_format($additionalCost->pivot->amount, 2) }}" />
                                                    </div>
                                                </div>
                                            @endforeach

                                        </div>

                                        {{-- Discount Row --}}
                                        <div class="row mb-1" id="div-discount">
                                            <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                                <label class="form-label fs-5" for="percentage-discount">Discount
                                                    (%)</label>
                                                <input type="number" class="form-control form-control-lg"
                                                    id="percentage-discount" placeholder="Discount %" disabled
                                                    value="{{ $salesPlan->discount_percentage }}" />
                                            </div>

                                            <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                                <label class="form-label fs-5" for="total-price-discount">Amount
                                                    (Rs)</label>
                                                <input type="text" class="form-control form-control-lg" disabled
                                                    id="total-price-discount" placeholder="Discount"
                                                    value="{{ number_format($salesPlan->discount_total) }}" />
                                            </div>
                                        </div>

                                        {{-- Total Amount Row --}}
                                        <div class="row mb-1">
                                            <div class="col-lg-6 col-md-6 col-sm-6 position-relative">

                                            </div>

                                            <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                                <hr>
                                                <label class="form-label fw-bolder fs-5" for="unit_rate_total">Total
                                                    (Rs)</label>
                                                <input type="text" class="form-control form-control-lg"
                                                    id="unit_rate_total" placeholder="Total"
                                                    value="{{ number_format($salesPlan->total_price, 2) }}"
                                                    disabled />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- PAYMENT PLAN --}}
                <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;"
                    id="installments_acard">
                    <div class="card-header">
                        <h3>2. INSTALLMENT DETAILS</h3>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped table-borderless"
                                        id="installments_table" style="position: relative;">
                                        <thead style="position: sticky; top: 0; z-index: 10;">
                                            <tr class="text-center">
                                                <th scope="col">#</th>
                                                <th scope="col">Installments</th>
                                                <th scope="col">Due Date</th>
                                                <th scope="col">Total Amount</th>
                                                {{-- <th scope="col">Remarks</th> --}}
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @forelse ($salesPlan->installments as $installment)
                                                <tr class="text-center">
                                                    <td>{{ $installment->installment_order }}</td>
                                                    <td>{{ $installment->details }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($installment->date)->format('F j, Y') }}
                                                    </td>
                                                    <td>{{ number_format($installment->amount, 2) }}</td>
                                                    {{-- <td>{{ $installment->remarks }}</td> --}}
                                                </tr>
                                            @empty
                                                <tr class="text-center">
                                                    <td colspan="5">No data found</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    <button class="btn btn-relief-outline-primary waves-effect waves-float waves-light btn-prev"
                        type="button">
                        <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                        <span class="align-middle d-sm-inline-block d-none">Previous</span>
                    </button>
                    <button class="btn btn-relief-outline-primary waves-effect waves-float waves-light btn-next"
                        type="button">
                        <span class="align-middle d-sm-inline-block d-none">Next</span>
                        <i data-feather="arrow-right" class="align-middle ms-sm-25 ms-0"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="card content shadow-none m-0" id="receipts" role="tabpanel" aria-labelledby="receipts-trigger">

            <div class="card-body">
                {{-- PAYMENT PLAN --}}
                <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;"
                    id="installments_acard">
                    <div class="card-header">
                        <h3>Receipts</h3>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped table-borderless"
                                        id="installments_table" style="position: relative;">
                                        <thead style="position: sticky; top: 0; z-index: 10;">
                                            <tr class="text-center">
                                                <th scope="col">#</th>
                                                <th scope="col">Installments</th>
                                                <th scope="col">Transaction Date</th>
                                                <th scope="col">Total Amount</th>
                                                <th scope="col">Mode of Payment</th>
                                                {{-- <th scope="col">Status</th> --}}
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @php
                                                $receipts = collect($salesPlan->receipts)
                                                    ->sortBy('created_date')
                                                    ->values()
                                                    ->all();
                                            @endphp

                                            @forelse ($receipts as $receipt)
                                                <tr class="text-center">
                                                    <td>{{ $loop->index + 1 }}</td>

                                                    @php
                                                        $installmentsInfo = implode(', ', json_decode($receipt->installment_number));
                                                    @endphp
                                                    <td>{{ $installmentsInfo }}</td>

                                                    <td>{!! editDateColumn($receipt->created_date) !!}</td>
                                                    <td>{{ number_format($receipt->amount_in_numbers, 2) }}</td>
                                                    <td>{{ $receipt->mode_of_payment }}</td>
                                                    {{-- <td>
                                                        @if ($receipt->status == 1)
                                                            <span class="badge badge-glow bg-success">Active</span>
                                                        @else
                                                            <span class="badge badge-glow bg-danger"></span>
                                                        @endif
                                                    </td> --}}
                                                </tr>
                                            @empty
                                                <tr class="text-center">
                                                    <td colspan="5">No data found</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    <button class="btn btn-relief-outline-primary waves-effect waves-float waves-light btn-prev"
                        type="button">
                        <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                        <span class="align-middle d-sm-inline-block d-none">Previous</span>
                    </button>
                    <button class="btn btn-relief-outline-primary waves-effect waves-float waves-light btn-next"
                        type="button">
                        <span class="align-middle d-sm-inline-block d-none">Next</span>
                        <i data-feather="arrow-right" class="align-middle ms-sm-25 ms-0"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="card content shadow-none m-0" id="booking-form" role="tabpanel"
            aria-labelledby="booking-form-trigger">

            <div class="card-body">

                <div class="row mb-1">
                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                        <div class="card m-0"
                            style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                            <div class="card-header">
                                <h3>Sales Person</h3>
                            </div>

                            <div class="card-body">

                                <div class="row mb-1">
                                    <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                                        <label class="form-label fs-5" for="sales_source_full_name">Sales
                                            Person</label>
                                        <input type="text" class="form-control form-control-lg"
                                            id="sales_source_full_name" name="sales_source[full_name]"
                                            placeholder="Sales Person" value="{{ $user->name }}" disabled />
                                    </div>

                                    <div class="col-lg-3 col-md-3 col-sm-3 position-relative">

                                        @php
                                            $roles = $user->roles->pluck('name')->toArray();
                                            $roles = implode(', ', $roles);
                                        @endphp

                                        <label class="form-label fs-5" for="sales_source_status">Status</label>
                                        <input type="text" class="form-control form-control-lg"
                                            id="sales_source_status" name="sales_source[status]" placeholder="Status"
                                            value="{{ $roles }}" disabled />
                                    </div>

                                    <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                                        <label class="form-label fs-5" for="sales_source_contact_no">Contact
                                            No</label>
                                        <input type="text" class="form-control form-control-lg"
                                            id="sales_source_contact_no" name="sales_source[contact_no]"
                                            placeholder="Contact No" value="{{ $user->phone_no }}" disabled />
                                        {{-- invalid-tooltip">{{ $message }}
                                    </div> --}}
                                    </div>

                                    <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                                        <label class="form-label fs-5" for="sales_source_lead_source">Lead
                                            Source</label>
                                        <input type="text" class="form-control form-control-lg"
                                            id="sales_source_lead_source" placeholder="Lead Source"
                                            value="{{ $salesPlan->leadSource->name }}" disabled />
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                {{-- Stakeholder Data --}}
                <div class="row mb-1">
                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                        <div class="card m-0"
                            style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                            <div class="card-header">
                                <h3>Customer</h3>
                            </div>

                            <div class="card-body">

                                <div class="row g-1 mb-1">
                                    <input type="hidden" name="application_form[stakeholder_id]"
                                        value="{{ $customer->id }}">

                                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                        <label class="form-label fs-5" for="customer_name">Name</label>
                                        <input type="text" class="form-control form-control-lg" id="customer_name"
                                            placeholder="Name" value="{{ $customer->full_name }}" disabled />
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                        <label class="form-label fs-5" for="customer_father_name">Father/Husband
                                            Name</label>
                                        <input type="text" class="form-control form-control-lg"
                                            id="customer_father_name" value="{{ $customer->father_name }}"
                                            placeholder="Father/Husband Name" disabled />
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                        <label class="form-label fs-5" for="customer_cnic">CNIC/Passport</label>
                                        <input type="text" class="form-control form-control-lg" id="customer_cnic"
                                            placeholder="CNIC/Passport"
                                            value="{{ cnicFormat($customer->cnic) ?? '-' }}" disabled />
                                    </div>
                                </div>

                                <div class="row g-1 mb-1">
                                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                        <label class="form-label fs-5" for="customer_ntn">NTN Number</label>
                                        <input type="text" class="form-control form-control-lg" id="customer_ntn"
                                            placeholder="NTN Number" value="{{ $customer->ntn ?? '-' }}" disabled />
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                        <label class="form-label fs-5" for="customer_phone">Cell</label>
                                        <input type="text" class="form-control form-control-lg"
                                            id="customer_phone" placeholder="Cell"
                                            value="{{ $customer->contact ?? '-' }}" disabled />
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                        <label class="form-label fs-5" for="customer_occupation">Occupation</label>
                                        <input type="text" class="form-control form-control-lg"
                                            id="customer_occupation" placeholder="Occupation"
                                            value="{{ $customer->occupation ?? '-' }}" disabled />
                                    </div>
                                </div>

                                <div class="row g-1 mb-1">
                                    <div class="col-lg-6 col-md-6 col-sm-12 position-relative">
                                        <label class="form-label fs-5" for="customer_address">Address</label>
                                        <input type="text" class="form-control form-control-lg"
                                            id="customer_address" placeholder="Address"
                                            value="{{ $customer->address ?? '-' }}" disabled />
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-12 position-relative">
                                        <label class="form-label fs-5" for="customer_comments">Comments</label>
                                        <input type="text" class="form-control form-control-lg"
                                            id="customer_comments" placeholder="Comments"
                                            value="{{ $customer->comments ?? '-' }}" disabled />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- PAYMENT PLAN --}}
                <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;"
                    id="installments_acard">
                    <div class="card-header">
                        {{-- <h3>INSTALLMENT DETAILS</h3> --}}
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped table-borderless"
                                        id="installments_table" style="position: relative;">
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

                                                @if (isset($salesPlan->additionalCosts[0]))
                                                    @foreach ($salesPlan->additionalCosts as $additionalCosts)
                                                        <th style="vertical-align: middle;" class="text-nowrap"
                                                            scope="col">
                                                            {{ $additionalCosts->name }}
                                                        </th>
                                                    @endforeach
                                                @else
                                                    <th style="vertical-align: middle;" scope="col">Face Charges
                                                    </th>
                                                @endif


                                                <th style="vertical-align: middle;" scope="col">Discount</th>
                                                <th style="vertical-align: middle;" scope="col">Total</th>
                                                <th style="vertical-align: middle;" scope="col">Downpayment</th>
                                            </tr>

                                            <tr class="text-center">
                                                @if (isset($salesPlan->additionalCosts[0]))
                                                    @foreach ($salesPlan->additionalCosts as $additionalCosts)
                                                        <th style="vertical-align: middle;" scope="col">
                                                            {{ $additionalCosts->unit_percentage }}%
                                                        </th>
                                                    @endforeach
                                                @else
                                                    <th style="vertical-align: middle;" scope="col">
                                                        %
                                                    </th>
                                                @endif

                                                <th style="vertical-align: middle;" scope="col">
                                                    {{ $salesPlan->discount_percentage }} %</th>
                                                <th style="vertical-align: middle;" scope="col">Value</th>
                                                <th style="vertical-align: middle;" scope="col">
                                                    {{ $salesPlan->down_payment_percentage }} %</th>
                                            </tr>

                                        </thead>

                                        <tbody>
                                            <tr class="text-center">
                                                <td>1</td>
                                                <td>{{ $unit->unit_number }}</td>
                                                <td>{{ $unit->gross_area }}</td>
                                                <td>{{ number_format($unit->price_sqft, 2) }}</td>
                                                @if (isset($salesPlan->additionalCosts[0]))
                                                    @foreach ($salesPlan->additionalCosts as $additionalCosts)
                                                        <td>
                                                            {{ number_format(($additionalCosts->unit_percentage / 100) * ($salesPlan->unit_price * $unit->gross_area)) }}
                                                        </td>
                                                    @endforeach
                                                @else
                                                    <td>-</td>
                                                @endif
                                                <td>{{ number_format($salesPlan->discount_total, 2) }}</td>
                                                <td>{{ number_format($salesPlan->total_price, 2) }}</td>
                                                <td>{{ number_format($salesPlan->down_payment_total, 2) }}</td>
                                            </tr>
                                            <tr class="text-center">
                                                <td colspan="4"></td>
                                                @if (isset($salesPlan->additionalCosts[0]))
                                                    @foreach ($salesPlan->additionalCosts as $additionalCosts)
                                                        <td>
                                                            {{ number_format(($additionalCosts->unit_percentage / 100) * ($salesPlan->unit_price * $unit->gross_area)) }}
                                                        </td>
                                                    @endforeach
                                                @else
                                                    <td>-</td>
                                                @endif
                                                <td>{{ number_format($salesPlan->discount_total, 2) }}</td>
                                                <td>{{ number_format($salesPlan->total_price, 2) }}</td>
                                                <td>{{ number_format($salesPlan->down_payment_total, 2) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;"
                    id="installments_acard">
                    <div class="card-header">
                        <h3>Deal Type</h3>
                    </div>

                    <div class="card-body">

                        <div class="row g-1">
                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="ideal-deal-check"
                                        name="application_form[deal_type]" value="ideal-deal"
                                        @if (isset($customer_file)) disabled @else checked @endif
                                        @if (isset($customer_file) && $customer_file->deal_type == 'ideal-deal') checked @endif>
                                    <label class="form-check-label" for="ideal-deal-check">Idea Deal</label>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="mark-down-check"
                                        name="application_form[deal_type]" value="mark_down"
                                        @if (isset($customer_file)) disabled @endif
                                        @if (isset($customer_file) && $customer_file->deal_type == 'mark_down') checked @endif>
                                    <label class="form-check-label" for="mark-down-check">Mark Down</label>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="special-case-check"
                                        name="application_form[deal_type]" value="special_case"
                                        @if (isset($customer_file)) disabled @endif
                                        @if (isset($customer_file) && $customer_file->deal_type == 'special_case') checked @endif>
                                    <label class="form-check-label" for="special-case-check">Special Case</label>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="adjustment-check"
                                        name="application_form[deal_type]" value="adjustment"
                                        @if (isset($customer_file)) disabled @endif
                                        @if (isset($customer_file) && $customer_file->deal_type == 'adjustment') checked @endif>
                                    <label class="form-check-label" for="adjustment-check">Adjustment</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    <button class="btn btn-relief-outline-primary waves-effect waves-float waves-light btn-prev"
                        type="button">
                        <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                        <span class="align-middle d-sm-inline-block d-none">Previous</span>
                    </button>
                    @if (!isset($customer_file))
                        @can('sites.file-managements.customers.units.files.store')
                            <button class="btn btn-relief-outline-success waves-effect waves-float waves-light btn-next"
                                type="submit">
                                <span class="align-middle d-sm-inline-block d-none">Save</span>
                            </button>
                        @endcan
                    @endif
                </div>
            </div>
        </div>

    </div>

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
            {{-- <i data-feather="chevron-right" class="font-medium-2"></i> --}}
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
            {{-- <i data-feather="chevron-right" class="font-medium-2"></i> --}}
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
            {{-- <i data-feather="chevron-right" class="font-medium-2"></i> --}}
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
            {{-- <i data-feather="chevron-right" class="font-medium-2"></i> --}}
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

        <div class="step" data-target="#rebate-form" role="tab" id="rebate-form-trigger">
            <button type="button" class="step-trigger">
                <span class="bs-stepper-box waves-effect waves-float waves-light" class="font-medium-3">6</span>
                <span class="bs-stepper-label">
                    <span class="bs-stepper-title">Booking Form</span>
                    <span class="bs-stepper-subtitle">Validate Booking Form</span>
                </span>
            </button>
        </div>
    </div>

    <div class="bs-stepper-content p-0" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">

        <div class="card content shadow-none m-0" id="applicaiton-form" role="tabpanel"
            aria-labelledby="applicaiton-form-trigger">
            <div class="card-body">

                <div class="row g-1 mb-2">
                    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                        <label class="form-label fs-5" for="registration_no">Registration No</label>
                        <input type="text" class="form-control form-control-lg" id="registration_no"
                            name="application_form[registration_no]" placeholder="Registration No" />
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                        <label class="form-label fs-5" for="application_no">Application No</label>
                        <input type="text" class="form-control form-control-lg" id="application_no"
                            name="application_form[application_no]" placeholder="Application No" />
                    </div>
                </div>

                <div class="row mb-1">
                    <div class="col-lg-10 col-md-10 col-sm-12">
                        {{-- Units Data --}}
                        <div class="row mb-1">
                            <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                                <div class="card m-0"
                                    style="border: 2px solid #eee; border-style: dashed; border-radius: 0;">
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
                        <label class="form-label fs-5" for="application_photo">Photo</label>
                        <input id="application_photo" type="file" class="filepond" name="application_form[photo]"
                            accept="image/png, image/jpeg" />
                    </div>
                </div>

                {{-- Stakeholder Data --}}
                <div class="row mb-1">
                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                        <div class="card m-0" style="border: 2px solid #eee; border-style: dashed; border-radius: 0;">
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
                                            placeholder="CNIC/Passport" value="{{ $customer->cnic ?? '-' }}"
                                            disabled />
                                    </div>
                                </div>

                                <div class="row g-1 mb-1">
                                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                        <label class="form-label fs-5" for="customer_address">Mail Address</label>
                                        <input type="text" class="form-control form-control-lg"
                                            id="customer_address" placeholder="Mail Address"
                                            value="{{ $customer->address ?? '-' }}" disabled />
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
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Next Of KIN Data --}}
                <div class="row mb-1">
                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                        <div class="card m-0" style="border: 2px solid #eee; border-style: dashed; border-radius: 0;">
                            <div class="card-header">
                                <h3>Next Of KIN</h3>
                            </div>

                            <div class="card-body">
                                <div class="row g-1 mb-1">
                                    <input type="hidden" name="application_form[stakeholder_id]"
                                        value="{{ !is_null($nextOfKin) ? $nextOfKin->id : '-' }}">

                                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                        <label class="form-label fs-5" for="customer_name">Name</label>
                                        <input type="text" class="form-control form-control-lg" id="customer_name"
                                            placeholder="Name"
                                            value="{{ !is_null($nextOfKin) ? $nextOfKin->full_name : '-' }}"
                                            disabled />
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                        <label class="form-label fs-5" for="customer_father_name">Father/Husband
                                            Name</label>
                                        <input type="text" class="form-control form-control-lg"
                                            id="customer_father_name"
                                            value="{{ !is_null($nextOfKin) ? $nextOfKin->father_name : '-' }}"
                                            placeholder="Father/Husband Name" disabled />
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                        <label class="form-label fs-5"
                                            for="customer_relationship">Relationship</label>
                                        <input type="text" class="form-control form-control-lg"
                                            id="customer_relationship"
                                            value="{{ !is_null($nextOfKin) ? $customer->relation : '-' }}"
                                            placeholder="Relationship" disabled />
                                    </div>
                                </div>

                                <div class="row g-1 mb-1">
                                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                        <label class="form-label fs-5" for="customer_cnic">CNIC/Passport</label>
                                        <input type="text" class="form-control form-control-lg" id="customer_cnic"
                                            placeholder="CNIC/Passport"
                                            value="{{ !is_null($nextOfKin) ? $nextOfKin->cnic : '-' }}" disabled />
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                        <label class="form-label fs-5" for="customer_phone">Cell</label>
                                        <input type="text" class="form-control form-control-lg"
                                            id="customer_phone" placeholder="Cell"
                                            value="{{ !is_null($nextOfKin) ? $nextOfKin->contact : '-' }}" disabled />
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                        <label class="form-label fs-5" for="customer_occupation">Occupation</label>
                                        <input type="text" class="form-control form-control-lg"
                                            id="customer_occupation" placeholder="Occupation"
                                            value="{{ !is_null($nextOfKin) ? $nextOfKin->occupation : '-' }}"
                                            disabled />
                                    </div>
                                </div>

                                <div class="row g-1 mb-1">
                                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                                        <label class="form-label fs-5" for="customer_address">Mail Address</label>
                                        <input type="text" class="form-control form-control-lg"
                                            id="customer_address" placeholder="Mail Address"
                                            value="{{ !is_null($nextOfKin) ? $nextOfKin->address : '-' }}" disabled />
                                    </div>
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

        <div class="card content shadow-none m-0" id="sales-agreement" role="tabpanel"
            aria-labelledby="sales-agreement-trigger">

            <div class="card-body">

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
                                                    value="{{ number_format($unit->price_sqft, 2) }}" disabled />
                                            </div>

                                            <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                                <label class="form-label fs-5" for="total-price-unit">Amount
                                                    (Rs)</label>
                                                <input type="text" class="form-control form-control-lg" disabled
                                                    id="total-price-unit" placeholder="Amount"
                                                    value="{{ number_format($unit->total_price, 2) }}" />
                                            </div>
                                        </div>

                                        <div id="div_additional_cost">
                                            {{-- Additional Cost Rows --}}

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
                                                    value="{{ $salesPlan->discount_total }}" />
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
                                                    value="{{ number_format($salesPlan->total_price + $total_additional_cost - $salesPlan->discount_total, 2) }}"
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
                                <table class="table table-hover table-striped table-borderless"
                                    id="installments_table" style="position: relative;">
                                    <thead style="position: sticky; top: 0; z-index: 10;">
                                        <tr class="text-center">
                                            <th scope="col">#</th>
                                            <th scope="col">Installments</th>
                                            <th scope="col">Due Date</th>
                                            <th scope="col">Total Amount</th>
                                            <th scope="col">Remarks</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @forelse ($salesPlan->installments as $installment)
                                        {{-- {{ dd($installment) }} --}}

                                        <tr class="text-center">
                                            <td>{{ $installment->installment_order }}</td>
                                            <td>{{ $installment->details }}</td>
                                            <td>{{ \Carbon\Carbon::parse($installment->date)->format('F j, Y') }}</td>
                                            <td>{{ number_format($installment->amount, 2) }}</td>
                                            <td>{{ $installment->remarks }}</td>
                                        </tr>
                                        @empty

                                        @endforelse
                                    </tbody>
                                </table>
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


        <div id="receipts" class="content" role="tabpanel" aria-labelledby="receipts-trigger">
            <div class="content-header">
                <h5 class="mb-0">Social Links</h5>
                <small>Enter Your Social Links.</small>
            </div>
            <div class="row">
                <div class="mb-1 col-md-6">
                    <label class="form-label" for="modern-twitter">Twitter</label>
                    <input type="text" id="modern-twitter" class="form-control"
                        placeholder="https://twitter.com/abc" />
                </div>
                <div class="mb-1 col-md-6">
                    <label class="form-label" for="modern-facebook">Facebook</label>
                    <input type="text" id="modern-facebook" class="form-control"
                        placeholder="https://facebook.com/abc" />
                </div>
            </div>
            <div class="row">
                <div class="mb-1 col-md-6">
                    <label class="form-label" for="modern-google">Google+</label>
                    <input type="text" id="modern-google" class="form-control"
                        placeholder="https://plus.google.com/abc" />
                </div>
                <div class="mb-1 col-md-6">
                    <label class="form-label" for="modern-linkedin">Linkedin</label>
                    <input type="text" id="modern-linkedin" class="form-control"
                        placeholder="https://linkedin.com/abc" />
                </div>
            </div>
            <div class="d-flex justify-content-between">
                <button class="btn btn-primary btn-prev">
                    <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                    <span class="align-middle d-sm-inline-block d-none">Previous</span>
                </button>
                <button class="btn btn-success btn-submit">Submit</button>
            </div>
        </div>
        <div id="booking-form" class="content" role="tabpanel" aria-labelledby="receipts-trigger">
            <div class="content-header">
                <h5 class="mb-0">Social Links</h5>
                <small>Enter Your Social Links.</small>
            </div>
            <div class="row">
                <div class="mb-1 col-md-6">
                    <label class="form-label" for="modern-twitter">Twitter</label>
                    <input type="text" id="modern-twitter" class="form-control"
                        placeholder="https://twitter.com/abc" />
                </div>
                <div class="mb-1 col-md-6">
                    <label class="form-label" for="modern-facebook">Facebook</label>
                    <input type="text" id="modern-facebook" class="form-control"
                        placeholder="https://facebook.com/abc" />
                </div>
            </div>
            <div class="row">
                <div class="mb-1 col-md-6">
                    <label class="form-label" for="modern-google">Google+</label>
                    <input type="text" id="modern-google" class="form-control"
                        placeholder="https://plus.google.com/abc" />
                </div>
                <div class="mb-1 col-md-6">
                    <label class="form-label" for="modern-linkedin">Linkedin</label>
                    <input type="text" id="modern-linkedin" class="form-control"
                        placeholder="https://linkedin.com/abc" />
                </div>
            </div>
            <div class="d-flex justify-content-between">
                <button class="btn btn-primary btn-prev">
                    <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                    <span class="align-middle d-sm-inline-block d-none">Previous</span>
                </button>
                <button class="btn btn-success btn-submit">Submit</button>
            </div>
        </div>
        <div id="rebate-form" class="content" role="tabpanel" aria-labelledby="receipts-trigger">
            <div class="content-header">
                <h5 class="mb-0">Social Links</h5>
                <small>Enter Your Social Links.</small>
            </div>
            <div class="row">
                <div class="mb-1 col-md-6">
                    <label class="form-label" for="modern-twitter">Twitter</label>
                    <input type="text" id="modern-twitter" class="form-control"
                        placeholder="https://twitter.com/abc" />
                </div>
                <div class="mb-1 col-md-6">
                    <label class="form-label" for="modern-facebook">Facebook</label>
                    <input type="text" id="modern-facebook" class="form-control"
                        placeholder="https://facebook.com/abc" />
                </div>
            </div>
            <div class="row">
                <div class="mb-1 col-md-6">
                    <label class="form-label" for="modern-google">Google+</label>
                    <input type="text" id="modern-google" class="form-control"
                        placeholder="https://plus.google.com/abc" />
                </div>
                <div class="mb-1 col-md-6">
                    <label class="form-label" for="modern-linkedin">Linkedin</label>
                    <input type="text" id="modern-linkedin" class="form-control"
                        placeholder="https://linkedin.com/abc" />
                </div>
            </div>
            <div class="d-flex justify-content-between">
                <button class="btn btn-primary btn-prev">
                    <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                    <span class="align-middle d-sm-inline-block d-none">Previous</span>
                </button>
                <button class="btn btn-success btn-submit">Submit</button>
            </div>
        </div>
    </div>
</div>

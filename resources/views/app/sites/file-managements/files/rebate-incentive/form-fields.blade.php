<div class="card content shadow-none m-0" id="rebate-form" role="tabpanel"
            aria-labelledby="rebate-form-trigger">

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
                                        {{-- invalid-tooltip">{{ $message }}</div> --}}
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
                                                <th style="vertical-align: middle;" scope="col">Face Charges</th>
                                                <th style="vertical-align: middle;" scope="col">Discount</th>
                                                <th style="vertical-align: middle;" scope="col">Total</th>
                                                <th style="vertical-align: middle;" scope="col">Downpayment</th>
                                                <th style="vertical-align: middle;" scope="col">Rebate</th>
                                            </tr>

                                            <tr class="text-center">
                                                <th style="vertical-align: middle;" scope="col">%</th>
                                                <th style="vertical-align: middle;" scope="col">
                                                    {{ $salesPlan->discount_percentage }} %</th>
                                                <th style="vertical-align: middle;" scope="col">Value</th>
                                                <th style="vertical-align: middle;" scope="col">
                                                    {{ $salesPlan->down_payment_percentage }} %</th>
                                                <th style="vertical-align: middle;" scope="col">5%</th>
                                            </tr>

                                        </thead>

                                        <tbody>
                                            <tr class="text-center">
                                                <td>1</td>
                                                <td>{{ $unit->unit_number }}</td>
                                                <td>{{ $unit->gross_area }}</td>
                                                <td>{{ number_format($unit->price_sqft, 2) }}</td>
                                                <td></td>
                                                <td>{{ number_format($salesPlan->discount_total, 2) }}</td>
                                                <td>{{ number_format($salesPlan->total_price, 2) }}</td>
                                                <td>{{ number_format($salesPlan->down_payment_total, 2) }}</td>
                                                <td>{{ number_format(469668, 2) }}</td>
                                            </tr>
                                            <tr class="text-center">
                                                <td colspan="4"></td>
                                                <td>-</td>
                                                <td>{{ number_format($salesPlan->discount_total, 2) }}</td>
                                                <td>{{ number_format($salesPlan->total_price, 2) }}</td>
                                                <td>{{ number_format($salesPlan->down_payment_total, 2) }}</td>
                                                <td>{{ number_format(469668, 2) }}</td>
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
                                        name="deal_type" value="ideal-deal" checked disabled>
                                    <label class="form-check-label" for="ideal-deal-check">Idea Deal</label>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="mark-down-check"
                                        name="deal_type" value="mark_down" disabled>
                                    <label class="form-check-label" for="mark-down-check">Mark Down</label>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="special-case-check"
                                        name="deal_type" value="special_case" disabled>
                                    <label class="form-check-label" for="special-case-check">Special Case</label>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="adjustment-check"
                                        name="deal_type" value="adjustment" disabled>
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
                    <button class="btn btn-relief-outline-primary waves-effect waves-float waves-light btn-next"
                        type="button">
                        <span class="align-middle d-sm-inline-block d-none">Next</span>
                        <i data-feather="arrow-right" class="align-middle ms-sm-25 ms-0"></i>
                    </button>
                </div>
            </div>
        </div>

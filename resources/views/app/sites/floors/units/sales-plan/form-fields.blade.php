<div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
    <div class="card-header">
        <h3>1. PRIMARY DATA</h3>
    </div>

    <div class="card-body">
        <div class="row mb-1">
            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                <label class="form-label fs-5" for="unit_no">Unit No</label>
                <input type="text" class="form-control form-control-lg" id="unit_no" name="unit[no]"
                    placeholder="Unit No" value="{{ $unit->floor_unit_number }}" readonly />
            </div>

            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                <label class="form-label fs-5" for="floor_no">Floor No</label>
                <input type="text" class="form-control form-control-lg" id="floor_no" name="unit[floor_no]"
                    placeholder="Floor No" value="{{ $floor->name }} ({{ $floor->short_label }})" readonly />
            </div>

            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                <label class="form-label fs-5" for="unit_type">Unit Type</label>
                <input type="text" class="form-control form-control-lg" id="unit_type" name="unit[type]"
                    placeholder="Unit Type" value="{{ $unit->type->name }}" readonly />
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                <label class="form-label fs-5" for="unit_size">Unit Size(sq.ft)</label>
                <input type="text" class="form-control form-control-lg" id="unit_size" name="unit[size]"
                    placeholder="Unit Size(sq.ft)" value="{{ $unit->gross_area }}" readonly />
            </div>
        </div>

        {{-- PRICING --}}
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                <div class="card m-0" style="border: 2px solid #eee; border-style: dashed; border-radius: 0;">
                    <div class="card-header">
                        <h3>PRICING</h3>
                    </div>

                    <div class="card-body">
                        {{-- Unit Rate Row --}}
                        <div class="row mb-1" id="div-unit">
                            <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                <label class="form-label fs-5" for="unit_price">Unit Price (Rs)</label>
                                <input type="number" min="0" class="form-control form-control-lg"
                                    id="unit_price" name="unit[price][unit]" placeholder="Unit Price"
                                    value="{{ $unit->price_sqft }}" />
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                <label class="form-label fs-5" for="total-price-unit">Amount (Rs)</label>
                                <input type="text" class="form-control form-control-lg" readonly
                                    id="total-price-unit" name="unit[price][total]" placeholder="Amount"
                                    value="{{ number_format($unit->total_price, 2) }}" />
                            </div>
                        </div>

                        <div id="div_additional_cost">
                            {{-- Additional Cost Rows --}}

                            @foreach ($additionalCosts as $key => $additionalCost)
                                @continue($additionalCost->has_child)

                                @php
                                    $additionalCostTotalAmount = ($unit->total_price * $additionalCost->site_percentage) / 100;
                                @endphp

                                <div class="row mb-1" id="div-{{ $additionalCost->slug }}-{{ $key }}"
                                    style="display: none;">

                                    <input type="hidden"
                                        name="unit[additional_cost][{{ $additionalCost->slug }}][status]"
                                        id="status-{{ $additionalCost->slug }}-{{ $key }}" value="false">

                                    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                        <label class="form-label fs-5"
                                            for="price-{{ $additionalCost->slug }}-{{ $key }}">{{ $additionalCost->name }}
                                            (%)
                                        </label>

                                        <input type="number" min="0" max="100" step="0.1"
                                            class="form-control form-control-lg additional-cost-percentage"
                                            id="percentage-{{ $additionalCost->slug }}-{{ $key }}"
                                            name="unit[additional_cost][{{ $additionalCost->slug }}][percentage]"
                                            placeholder="{{ $additionalCost->name }}"
                                            value="{{ $additionalCost->site_percentage }}" />

                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                        <label class="form-label fs-5"
                                            for="total-price-{{ $additionalCost->slug }}-{{ $key }}">Amount
                                            (Rs)</label>

                                        <input type="text"
                                            class="form-control form-control-lg additional-cost-total-price"
                                            id="total-price-{{ $additionalCost->slug }}-{{ $key }}"
                                            name="unit[additional_cost][{{ $additionalCost->slug }}][total]" readonly
                                            placeholder="Amount"
                                            value="{{ number_format($additionalCostTotalAmount, 2) }}" />
                                    </div>
                                </div>
                            @endforeach

                        </div>

                        {{-- Discount Row --}}
                        <div class="row mb-1" id="div-discount">
                            <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                <label class="form-label fs-5" for="percentage-discount">Discount (%)</label>
                                <input type="number" min="0" max="100" step="0.1"
                                    class="form-control form-control-lg" id="percentage-discount"
                                    name="unit[discount][percentage]" placeholder="Discount %" value="0" />
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                <label class="form-label fs-5" for="total-price-discount">Amount (Rs)</label>
                                <input type="text" class="form-control form-control-lg" readonly
                                    id="total-price-discount" name="unit[discount][total]" placeholder="Discount"
                                    value="0.00" />
                            </div>
                        </div>

                        {{-- Total Amount Row --}}
                        <div class="row mb-1">
                            <div class="col-lg-6 col-md-6 col-sm-6 position-relative">

                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                <hr>
                                <label class="form-label fw-bolder fs-5" for="unit_rate_total">Total (Rs)</label>
                                <input type="text" class="form-control form-control-lg" id="unit_rate_total"
                                    name="unit[grand_total]" placeholder="Total"
                                    value="{{ number_format($unit->total_price, 2) }}" readonly />
                            </div>
                        </div>

                        {{-- Downpayment Row --}}
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                <label class="form-label fs-5" for="unit_downpayment_percentage">Down Payment
                                    (%)</label>
                                <input type="number" class="form-control form-control-lg"
                                    id="unit_downpayment_percentage" name="unit[downpayment][percentage]"
                                    placeholder="Down Payment %" min="0" max="100"
                                    value="{{ $site->siteConfiguration->site_down_payment_percentage }}.0"
                                    step="0.1" />
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                <label class="form-label fs-5" for="unit_downpayment_total">Amount (Rs)</label>
                                <input type="text" class="form-control form-control-lg" readonly
                                    id="unit_downpayment_total" name="unit[downpayment][total]"
                                    placeholder="Amount" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;"
    id="installments_acard">
    <div class="card-header">
        <h3>2. INSTALLMENT DETAILS</h3>
    </div>

    <div class="card-body">
        <div class="row mb-1">
            <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                <div class="row custom-options-checkable g-1">

                    <input type="hidden" id="base-installment" value="0">
                    <div class="col-md-5">
                        <input class="custom-option-item-check installment_type_radio" type="radio"
                            name="installments[types][type]" id="installment_quarterly" value="quarterly" checked />
                        <label class="custom-option-item p-1" for="installment_quarterly">
                            <span class="d-flex justify-content-between flex-wrap mb-50">
                                <span class="fw-bolder">Quarterly</span>
                                <span class="fw-bolder">3 Months</span>
                            </span>
                            <small class="d-block">Installment will be calculated on quarterly basis</small>
                        </label>
                    </div>

                    <div class="col-md-5">
                        <input class="custom-option-item-check installment_type_radio" type="radio"
                            name="installments[types][type]" id="installment_monthly" value="monthly" />
                        <label class="custom-option-item p-1" for="installment_monthly">
                            <span class="d-flex justify-content-between flex-wrap mb-50">
                                <span class="fw-bolder">Monthly</span>
                                <span class="fw-bolder">1 Month</span>
                            </span>
                            <small class="d-block">Installment will be calculated on montly basis</small>
                        </label>
                    </div>

                    <div class="col-md-2">
                        <p class="m-0 fw-bolder d-block mb-1">How Many (<span id="how_many">Quaters</span>)?</p>
                        <div class="d-flex justify-content-center align-items-center">
                            <div class="input-group input-group-lg ">
                                <input type="number" min="0" class="touchspin-icon"
                                    name="installments[types][value]" value="1" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-1">
            <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                <div class="row g-1">
                    <div class="col-md-12">
                        <label class="form-label fs-5" for="installments_start_date">Installments Start Date</label>
                        <input type="text" id="installments_start_date" name="installments[start_date]" readonly
                            class="form-control" placeholder="YYYY-MM-DD" />
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                <div class="card m-0" style="border: 2px solid #eee; border-style: dashed; border-radius: 0;">
                    <div class="card-body">
                        <div class="table-responsive" style="max-height: 50rem; overflow-y: auto;">

                            <table class="table table-hover table-striped table-borderless" id="installments_table"
                                style="position: relative;">
                                <thead style="position: sticky; top: 0; z-index: 10;">
                                    <tr class="text-center">
                                        <th scope="col">#</th>
                                        <th scope="col">Installments</th>
                                        <th scope="col">Due Date</th>
                                        <th scope="col">Total Amount</th>
                                        <th scope="col">Remarks</th>
                                    </tr>
                                </thead>

                                <tbody id="dynamic_installment_rows">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="card" id="additional_expense_card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
    <div class="card-header">
        <h3>Additional Expenses</h3>
    </div>
    <div class="card-body">
        <div class="expenses-list">
            <div data-repeater-list="expenses">
                <div data-repeater-item>
                    <div class="card m-0">
                        <div class="card-body">
                            <div>
                                <div class="row mb-1">
                                    <div class="col-lg-3 col-md-3 col-sm-12 position-relative">
                                        <label class="form-label fs-5"
                                            for="expense_label_{{ $key }}">Expense label</label>
                                        <input type="text"
                                            class="form-control form-control-lg @error('expense_label') is-invalid @enderror"
                                            id="expense_label_{{ $key }}"
                                            name="expenses[{{ $key }}][expense_label]"
                                            placeholder="Expense Label" />
                                    </div>

                                    <div class="col-lg-3 col-md-3 col-sm-12 position-relative">
                                        <label class="form-label fs-5" for="expense_due_date">Expense Due Date</label>
                                        <input type="text" id="expense_due_date"
                                            name="expenses[{{ $key }}][due_date]" readonly
                                            class="form-control form-control-lg expense_due_date"
                                            placeholder="YYYY-MM-DD" />
                                    </div>

                                    <div class="col-lg-3 col-md-3 col-sm-12 position-relative">
                                        <label class="form-label fs-5" for="expense_amount">Amount</label>
                                        <input type="number" min="0" class="form-control form-control-lg"
                                            id="expense_amount" name="expenses[{{ $key }}][amount]"
                                            placeholder="Amount">
                                    </div>

                                    <div class="col-lg-3 col-md-3 col-sm-12 position-relative">
                                        <label class="form-label fs-5" for="expense_remarks">Remarks</label>
                                        <input type="text" class="form-control form-control-lg"
                                            id="expense_remarks" name="expenses[{{ $key }}][remarks]"
                                            placeholder="Remarks">
                                    </div>
                                </div>

                                <div class="row mb-1">
                                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                                        <div class="d-flex h-100 justify-content-end align-items-end">
                                            <div>
                                                <button
                                                    class="btn btn-relief-outline-danger waves-effect waves-float waves-light"
                                                    data-repeater-delete id="delete-contact-person" type="button">
                                                    <i data-feather="x" class="me-25"></i>
                                                    <span>Delete</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <button class="btn btn-relief-outline-primary waves-effect waves-float waves-light"
                        id="add-new-expense" type="button" data-repeater-create>
                        <i data-feather="plus" class="me-25"></i>
                        <span>Add New</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;"
    id="stakeholders_card">
    <div class="card-header justify-content-between">
        <h3>3. STAKEHOLDER DATA (LEAD'S DATA)</h3>
        <div id="div_stakeholder_type">
            @forelse ($stakeholderTypes as $stakeholderType)
                <p class="badge badge-light-danger fs-5 ms-auto me-1">{{ $stakeholderType }}-000</p>
            @empty
            @endforelse
        </div>
    </div>

    <div class="card-body">

        <div class="row mb-1">
            <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                <label class="form-label" style="font-size: 15px" for="stackholders">Stakeholders</label>
                <select class="form-select" id="stackholders" name="stackholder[stackholder_id]">
                    <option value="0">Create new Stakeholder...</option>
                    @forelse ($stakeholders as $stakeholder)
                        <option value="{{ $stakeholder->id }}">{{ $stakeholder->full_name }} s/o
                            {{ $stakeholder->father_name }} {{ $stakeholder->cnic }}, {{ $stakeholder->contact }}
                        </option>
                    @empty
                    @endforelse
                </select>
            </div>
        </div>

        <div class="row mb-1">
            {{-- <input type="hidden" id="stackholder_id" name="stackholder[stackholder_id]" value="0" /> --}}
            <div class="col-lg-3 col-md-3 col-sm-12 position-relative">
                <label class="form-label fs-5" for="stackholder_full_name">Full Name</label>
                <input type="text" class="form-control form-control-lg" id="stackholder_full_name"
                    name="stackholder[full_name]" placeholder="Full Name" />
            </div>

            <div class="col-lg-3 col-md-3 col-sm-12 position-relative">
                <label class="form-label fs-5" for="stackholder_father_name">Father Name</label>
                <input type="text" class="form-control form-control-lg" id="stackholder_father_name"
                    name="stackholder[father_name]" placeholder="Father Name" />
            </div>

            <div class="col-lg-3 col-md-3 col-sm-12 position-relative">
                <label class="form-label fs-5" for="stackholder_occupation">Occupation</label>
                <input type="text" class="form-control form-control-lg" id="stackholder_occupation"
                    name="stackholder[occupation]" placeholder="Occupation" />
            </div>

            <div class="col-lg-3 col-md-3 col-sm-12 position-relative">
                <label class="form-label fs-5" for="stackholder_designation">Designation</label>
                <input type="text" class="form-control form-control-lg" id="stackholder_designation"
                    name="stackholder[designation]" placeholder="Designation" />
            </div>
        </div>

        <div class="row mb-1">

            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                <label class="form-label fs-5" for="stackholder_ntn">NTN</label>
                <input type="text" class="form-control form-control-lg" id="stackholder_ntn"
                    name="stackholder[ntn]" placeholder="NTN" />
            </div>

            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                <label class="form-label fs-5" for="stackholder_cnic">CNIC</label>
                <input type="text" class="form-control form-control-lg" id="stackholder_cnic"
                    name="stackholder[cnic]" placeholder="CNIC" />
            </div>

            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                <label class="form-label fs-5" for="stackholder_contact">Contact</label>
                <input type="text" class="form-control form-control-lg" id="stackholder_contact"
                    name="stackholder[contact]" placeholder="Contact" />
            </div>
        </div>

        <div class="row mb-1">
            <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                <label class="form-label fs-5" for="stackholder_address">Address</label>
                <textarea class="form-control form-control-lg" id="stackholder_address" name="stackholder[address]"
                    placeholder="Address" rows="5"></textarea>
            </div>
        </div>
    </div>
</div>

<div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
    <div class="card-header">
        <h3>4. SALES SOURCE</h3>
    </div>

    <div class="card-body">
        <div class="row mb-1">
            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                <label class="form-label fs-5" for="sales_source_full_name">Sales Person</label>
                <input type="text" class="form-control form-control-lg" id="sales_source_full_name"
                    name="sales_source[full_name]" placeholder="Sales Person" value="{{ $user->name }}"
                    disabled />
            </div>

            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">

                @php
                    $roles = $user->roles->pluck('name')->toArray();
                    $roles = implode(', ', $roles);
                @endphp

                <label class="form-label fs-5" for="sales_source_status">Status</label>
                <input type="text" class="form-control form-control-lg" id="sales_source_status"
                    name="sales_source[status]" placeholder="Status" value="{{ $roles }}" disabled />
            </div>

            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                <label class="form-label fs-5" for="sales_source_contact_no">Contact No</label>
                <input type="text" class="form-control form-control-lg" id="sales_source_contact_no"
                    name="sales_source[contact_no]" placeholder="Contact No" value="{{ $user->phone_no }}"
                    disabled />
                {{-- invalid-tooltip">{{ $message }}</div> --}}
            </div>
        </div>

        <div class="row mb-1 g-1">
            <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                <label class="form-label" style="font-size: 15px" for="sales_source_lead_source">Lead Source</label>
                <select class="form-select form-select-lg" id="sales_source_lead_source"
                    name="sales_source[lead_source]">
                    <option value="0">Create new Lead Source</option>
                    @forelse ($leadSources as $leadSource)
                        <option value="{{ $leadSource->id }}">{{ $leadSource->name }}</option>
                    @empty
                    @endforelse
                </select>
            </div>

            <div class="col-lg-12 col-md-12 col-sm-6 position-relative">
                <div id="div_sales_source_lead_source">
                    <label class="form-label fs-5" for="sales_source_new">New Sale Source</label>
                    <input type="text" class="form-control form-control-lg" id="sales_source_new"
                        name="sales_source[new]" placeholder="New Sale Source"
                        value="{{ old('sales_source.new') }}" />
                </div>
            </div>
        </div>

    </div>
</div>

<div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
    <div class="card-header">
        <h3>5. COMMENTS </h3>
    </div>

    <div class="card-body">
        <div class="row mb-1 g-1">
            {{-- <div class="col-lg-6 col-md-6 col-sm-12 position-relative">
                <label class="form-label fs-5" for="comments">Auto Generated Comments</label>
                <textarea class="form-control form-control-lg" id="auto_generated_comments" name="comments[auto_generated]"
                    placeholder="Auto Generated Comments" rows="5" readonly></textarea>
            </div> --}}

            <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                <label class="form-label fs-5" for="comments">Comments</label>
                <textarea class="form-control form-control-lg" id="custom_comments" name="comments[custom]" placeholder="Comments"
                    rows="5"></textarea>
            </div>
        </div>
    </div>
</div>

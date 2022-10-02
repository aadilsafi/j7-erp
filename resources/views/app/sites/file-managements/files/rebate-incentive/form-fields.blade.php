<div style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;" class="card content shadow-none m-0"
    id="rebate-form" role="tabpanel" aria-labelledby="rebate-form-trigger">
    <div class="card-body">
        <div class="row mb-1">
            <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                <div class="card m-0" style="border: 2px solid #eee; border-style: dashed; border-radius: 0;">
                    <div class="card-body">
                        <div class="row mb-1">
                            <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                <label class="form-label" style="font-size: 15px" for="unit_id">
                                    <h6 class="mb-1">Select Unit</h6>
                                </label>
                                <select id="unit_id"
                                    class="select2 form-select  unit_id @error('unit_id') is-invalid @enderror"
                                    name="unit_id" onchange="getData(this.options[this.selectedIndex].value)">
                                    <option selected>Select Unit No</option>
                                    @foreach ($units as $row)
                                        <option value="{{ $row->id }}">
                                            {{ $row->name }} ( {{ $row->floor_unit_number }} -
                                            {{ $row->floor->name }} - {{ $row->type->name }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('unit_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                <label class="form-label" style="font-size: 15px" for="floor">
                                    <h6 class="mb-1">Rebate %</h6>
                                </label>
                                <input min="0" id="rebate_percentage" onchange="rebateValue()" type="number"
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
                <div class="card m-0" style="border: 2px solid #eee; border-style: dashed; border-radius: 0;">
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
                                    name="sales_source_role" placeholder="Status" value="{{ $roles }}"
                                    disabled />
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                                <label class="form-label fs-5" for="sales_source_contact_no">Contact
                                    No</label>
                                <input type="text" class="form-control form-control-lg" id="sales_source_contact_no"
                                    name="sales_source_contact_no" placeholder="Contact No"
                                    value="{{ Auth::user()->phone_no }}" disabled />
                                {{-- invalid-tooltip">{{ $message }}</div> --}}
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                                <label class="form-label fs-5" for="sales_source_lead_source">Lead
                                    Source</label>
                                <input type="text" name="sales_source_lead_source"
                                    class="form-control form-control-lg" id="sales_source_lead_source"
                                    placeholder="Lead Source" value="" disabled />
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{-- Stakeholder Data --}}
        <div class="row mb-1 hideDiv">
            <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                <div class="card m-0" style="border: 2px solid #eee; border-style: dashed; border-radius: 0;">
                    <div class="card-header">
                        <h3>Customer</h3>
                    </div>

                    <div class="card-body">

                        <div class="row g-1 mb-1">
                            <input id="stakeholder_id" type="hidden" name="stakeholder_id" value="">

                            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                <label class="form-label fs-5" for="customer_name">Name</label>
                                <input type="text" class="form-control form-control-lg" id="customer_name"
                                    placeholder="Name" value="" disabled />
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                <label class="form-label fs-5" for="customer_father_name">Father/Husband
                                    Name</label>
                                <input type="text" class="form-control form-control-lg" id="customer_father_name"
                                    value="" placeholder="Father/Husband Name" disabled />
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                <label class="form-label fs-5" for="customer_cnic">CNIC/Passport</label>
                                <input type="text" class="form-control form-control-lg" id="customer_cnic"
                                    placeholder="CNIC/Passport" value="" disabled />
                            </div>
                        </div>

                        <div class="row g-1 mb-1">
                            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                <label class="form-label fs-5" for="customer_address">Mail Address</label>
                                <input type="text" class="form-control form-control-lg" id="customer_address"
                                    placeholder="Mail Address" value="" disabled />
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                <label class="form-label fs-5" for="customer_phone">Cell</label>
                                <input type="text" class="form-control form-control-lg" id="customer_phone"
                                    placeholder="Cell" value="" disabled />
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                <label class="form-label fs-5" for="customer_occupation">Occupation</label>
                                <input type="text" class="form-control form-control-lg" id="customer_occupation"
                                    placeholder="Occupation" value="" disabled />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- PAYMENT PLAN --}}
        <div class="card hideDiv" style="border: 2px solid #eee; border-style: dashed; border-radius: 0;"
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
                                        <th style="vertical-align: middle;" scope="col">Face Charges</th>
                                        <th style="vertical-align: middle;" scope="col">Discount</th>
                                        <th style="vertical-align: middle;" scope="col">Total</th>
                                        <th style="vertical-align: middle;" scope="col">Downpayment</th>
                                        <th style="vertical-align: middle;" scope="col">Rebate</th>
                                    </tr>

                                    <tr class="text-center">
                                        <th style="vertical-align: middle;" scope="col">%</th>
                                        <th style="vertical-align: middle;" scope="col"> %</th>
                                        <th style="vertical-align: middle;" scope="col">Value</th>
                                        <th style="vertical-align: middle;" scope="col"> %</th>
                                        <th style="vertical-align: middle;" scope="col">%</th>
                                    </tr>
                                    <input type="hidden" value="" id="unit_total">
                                </thead>

                                <tbody>
                                    <tr class="text-center">
                                        <td>1</td>
                                        <td id="td_unit_id">-</td>
                                        <td id="td_unit_area">-</td>
                                        <td id="td_unit_rate">-</td>
                                        <td id="td_unit_facing_charges">-</td>
                                        <td id="td_unit_discount">-</td>
                                        <td id="td_unit_total">-</td>
                                        <td id="td_unit_downpayment">-</td>
                                        <td id="td_rebate">-</td>
                                    </tr>
                                    <tr class="text-center">
                                        <td colspan="4">
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

        <div class="card hideDiv" style="border: 2px solid #eee; border-style: dashed; border-radius: 0;"
            id="installments_acard">
            <div class="card-header">
                <h3>Deal Type</h3>
            </div>

            <div class="card-body">
                <div class="row g-1">
                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" id="ideal-deal-check" name="deal_type"
                                value="ideal-deal">
                            <label class="form-check-label" for="ideal-deal-check">Idea Deal</label>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" id="mark-down-check" name="deal_type"
                                value="mark_down">
                            <label class="form-check-label" for="mark-down-check">Mark Down</label>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" id="special-case-check" name="deal_type"
                                value="special_case">
                            <label class="form-check-label" for="special-case-check">Special Case</label>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" id="adjustment-check" name="deal_type"
                                value="adjustment">
                            <label class="form-check-label" for="adjustment-check">Adjustment</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="row d-flex align-items-end">

    <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
        <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;"
            id="stakeholders_card">
            <div class="card-header justify-content-between">
                <h3> Required Data </h3>
            </div>

            <div class="card-body">
                <div class="row mb-1">
                    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                        <label class="form-label fs-5" for="new_resale_rate">New Resale Rate <span
                                class="text-danger">*</span></label>
                        <input type="text" required name="new_resale_rate"
                            class="form-control form-control-lg amountFormat" {{ isset($resale) ? 'disabled' : '' }}
                            id="new_resale_rate" placeholder=" Price per sq ft"
                            value="{{ isset($resale) ? number_format($resale->new_resale_rate) : '' }}" />
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                        <label class="form-label fs-5" for="premium_demand">Premium Demand <span
                                class="text-danger">*</span></label>
                        <input type="text" required name="premium_demand"
                            class="form-control form-control-lg amountFormat" {{ isset($resale) ? 'disabled' : '' }}
                            id="premium_demand" placeholder=" Premium Demand Rate"
                            value="{{ isset($resale) ? number_format($resale->premium_demand) : '' }}" />
                    </div>
                </div>

                <div class="row mb-1">
                    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                        <label class="form-label fs-5" for="marketing_service_charges">Marketing Service Charges
                        </label>
                        <input type="text" name="marketing_service_charges"
                            class="form-control form-control-lg amountFormat" {{ isset($resale) ? 'disabled' : '' }}
                            id="marketing_service_charges" placeholder=" Marketing Service Charges "
                            value="{{ isset($resale) ? number_format($resale->marketing_service_charges) : '' }}" />
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                        <label class="form-label fs-5" for="stackholder_father_name">Remarks <span
                                class="text-danger">*</span></label>
                        <input type="text" name="amount_remarks" required class="form-control form-control-lg"
                            id="remarks" {{ isset($resale) ? 'disabled' : '' }} placeholder=" Remarks"
                            value="{{ isset($resale) ? $resale->amount_remarks : '' }}" />
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div id="sellerInformation" class="col-lg-12 col-md-12 col-sm-12 position-relative">
        <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;" id="">
            <input type="hidden" value="{{ $customer->id }}" name="customer_id">
            <div class="card-header justify-content-between">
                <h3> Owner Information </h3>
            </div>

            <div class="card-body">

                <div class="row mb-1">
                    <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                        <label class="form-label fs-5" for="stackholder_full_name">Full Name</label>
                        <input type="text" readonly value="{{ $customer->full_name }}"
                            class="form-control form-control-lg" id="" placeholder="Full Name" />
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                        <label class="form-label fs-5" for="stackholder_father_name">Father / Husband Name</label>
                        <input type="text" readonly value="{{ $customer->father_name }}"
                            class="form-control form-control-lg" id="" placeholder="Father / Husband Name" />
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                        <label class="form-label fs-5" for="stackholder_occupation">Occupation</label>
                        <input type="text" readonly value="{{ $customer->occupation }}"
                            class="form-control form-control-lg" id="" placeholder="Occupation" />
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                        <label class="form-label fs-5" for="stackholder_designation">Designation</label>
                        <input type="text" readonly value="{{ $customer->designation }}"
                            class="form-control form-control-lg" id="" placeholder="Designation" />
                    </div>
                </div>

                <div class="row mb-1">

                    <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                        <label class="form-label fs-5" for="stackholder_ntn">NTN</label>
                        <input type="text" readonly value="{{ $customer->ntn }}"
                            class="form-control form-control-lg" id="" placeholder="NTN" />
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                        <label class="form-label fs-5" for="stackholder_cnic">CNIC</label>
                        <input type="text" readonly value="{{ cnicFormat($customer->cnic) }}"
                            class="form-control form-control-lg" id="" placeholder="CNIC" />
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                        <label class="form-label fs-5" for="stackholder_contact">Contact</label>
                        <input type="text" readonly value="{{ $customer->contact }}"
                            class="form-control form-control-lg" id="" placeholder="Contact" />
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                        <label class="form-label fs-5" for="stackholder_contact">Optional Contact</label>
                        <input type="number" readonly value="{{ $customer->optional_contact }}"
                            class="form-control form-control-lg" id="" placeholder="Optional Contact" />
                    </div>
                </div>

                <div class="row mb-1">
                    <div class="col-lg-6 col-md-6 col-sm-12 position-relative">
                        <label class="form-label fs-5" for="stackholder_address">Address</label>
                        <textarea class="form-control  form-control-lg" readonly id="" name="" placeholder="Address"
                            rows="5">{{ $customer->address }}</textarea>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-12 position-relative">
                        <label class="form-label fs-5" for="stackholder_comments">Comments</label>
                        <textarea class="form-control form-control-lg" readonly id="" name="" placeholder="Address"
                            rows="5">{{ $customer->comments }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="unitData" class="col-lg-12 col-md-12 col-sm-12 position-relative">
        <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;"
            id="stakeholders_card">
            <input type="hidden" value="{{ $unit->id }}" name="unit_id">
            <div class="card-header justify-content-between">
                <h3>Old Unit Data </h3>
            </div>

            <div class="card-body">

                <div class="row mb-1">

                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                        <label class="form-label fs-5" for="stackholder_occupation">Unit No</label>
                        <input type="text" readonly value="{{ $unit->floor_unit_number }}"
                            class="form-control form-control-lg" id="stackholder_occupation" placeholder="Unit No" />
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                        <label class="form-label fs-5" for="stackholder_full_name">Unit Name</label>
                        <input type="text" readonly value="{{ $unit->name }}"
                            class="form-control form-control-lg" id="stackholder_full_name"
                            placeholder="Unit Name" />
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                        <label class="form-label fs-5" for="stackholder_father_name">Unit Type</label>
                        <input type="text" readonly value="{{ $unit->type->name }}"
                            class="form-control form-control-lg" id="stackholder_father_name"
                            placeholder="Unit Type" />
                    </div>

                </div>

                <div class="row mb-1">

                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                        <label class="form-label fs-5" for="stackholder_occupation">Gross Area</label>
                        <input type="text" readonly value="{{ number_format($unit->gross_area) }}"
                            class="form-control form-control-lg" id="stackholder_occupation" placeholder="Unit No" />
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                        <label class="form-label fs-5" for="stackholder_full_name">Price Per Sqft</label>
                        <input type="text" readonly value="{{ number_format($unit->price_sqft) }}"
                            class="form-control form-control-lg" id="stackholder_full_name"
                            placeholder="Unit Name" />
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                        <label class="form-label fs-5" for="stackholder_father_name">Total Price</label>
                        <input type="text" readonly value="{{ number_format($unit->total_price) }}"
                            class="form-control form-control-lg" id="stackholder_father_name"
                            placeholder="Unit Type" />
                    </div>

                </div>

            </div>
        </div>
    </div>

    <div id="SalesPlanData" class="col-lg-12 col-md-12 col-sm-12 position-relative">
        <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;"
            id="stakeholders_card">
            <input type="hidden" value="{{ $unit->id }}" name="unit_id">
            <div class="card-header justify-content-between">
                <h3> Sales Plan Information </h3>
            </div>

            <div class="card-body">

                <div class="row mb-1">

                    <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                        <label class="form-label fs-5" for="stackholder_full_name">Price Per Sqft</label>
                        <input type="text" readonly value="{{ number_format($salesPlan->unit_price) }}"
                            class="form-control form-control-lg" id="stackholder_full_name"
                            placeholder="Unit Name" />
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                        <label class="form-label fs-5" for="stackholder_father_name">Total Price</label>
                        <input type="text" readonly value="{{ number_format($salesPlan->total_price) }}"
                            class="form-control form-control-lg" id="stackholder_father_name"
                            placeholder="Unit Type" />
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                        <label class="form-label fs-5" for="stackholder_occupation">Downpayment %</label>
                        <input type="text" readonly
                            @if (isset($unit->salesPlan[0])) value="{{ number_format($unit->salesPlan[0]['down_payment_percentage']) }}"
                            @else
                            value="{{ number_format($unit->CancelsalesPlan[0]['down_payment_percentage']) }}" @endif
                            class="form-control form-control-lg" id="stackholder_occupation" placeholder="Unit No" />
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                        <label class="form-label fs-5" for="stackholder_full_name">Downpayment Total</label>
                        <input type="text" readonly
                            @if (isset($unit->salesPlan[0])) value="{{ number_format($unit->salesPlan[0]['down_payment_total']) }}"
                            @else
                            value="{{ number_format($unit->CancelsalesPlan[0]['down_payment_total']) }}" @endif
                            class="form-control form-control-lg" id="stackholder_full_name"
                            placeholder="Unit Name" />
                    </div>

                </div>

                <div class="row mb-1">
                    <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                        <label class="form-label fs-5" for="amount_to_be_refunded">Paid Amount</label>
                        <input type="text" disabled required name="paid_amount"
                            class="form-control form-control-lg" id="paid_amount" placeholder=" Paid Amount"
                            value="{{ isset($total_paid_amount) ? number_format($total_paid_amount) : '' }}" />
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                        <label class="form-label fs-5 text-nowrap" for="stackholder_father_name">Installments
                            Received</label>
                        <input type="text" readonly
                            value="{{ count($paid_instalments) + count($partially_paid_instalments) }}"
                            class="form-control form-control-lg" id="stackholder_father_name"
                            placeholder="Unit Type" />
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                        <label class="form-label fs-5 text-nowrap" for="stackholder_father_name">Installments
                            Pending</label>
                        <input type="text" readonly
                            value="{{ count($un_paid_instalments) + count($partially_paid_instalments) }}"
                            class="form-control form-control-lg" id="stackholder_father_name"
                            placeholder="Unit Type" />
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                        <label class="form-label fs-5" for="stackholder_father_name">Date Of Purchase</label>
                        <input type="text" readonly
                            @if (isset($unit->salesPlan[0])) value="{{ date_format(new DateTime($unit->salesPlan[0]['created_date']), 'D d-M-Y') }}"
                            @else
                                value="{{ date_format(new DateTime($unit->CancelsalesPlan[0]['created_date']), 'D d-M-Y') }}" @endif
                            class="form-control form-control-lg" id="stackholder_father_name"
                            placeholder="Unit Type" />
                    </div>

                </div>

            </div>
        </div>
    </div>

    <div id="installments" class="col-lg-12 col-md-12 col-sm-12 position-relative">
        <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;"
            id="">
            <div class="card-header justify-content-between">
                <h3> Installments Information </h3>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                        <div class="card m-0" style="border: 2px solid #eee; border-style: dashed; border-radius: 0;">
                            <div class="card-body">
                                <div class="table-responsive" style="max-height: 50rem; overflow-y: auto;">

                                    <table class="table table-hover table-striped table-borderless"
                                        id="installments_table" style="position: relative;">
                                        <thead style="position: sticky; top: 0; z-index: 10;">
                                            <tr class="text-center text-nowrap">
                                                <th scope="col">#</th>
                                                <th scope="col">Installments</th>
                                                <th scope="col">Due Date</th>
                                                <th scope="col">Total Amount</th>
                                                <th scope="col">Paid Amount</th>
                                                <th scope="col">Remaining Amount</th>
                                                <th scope="col">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody id="dynamic_installment_rows">
                                            @foreach ($salesPlan->installments as $intsallment)
                                                <tr class="text-center text-nowrap">
                                                    <td>{{ $intsallment->installment_order }}</td>
                                                    <td>{{ $intsallment->details }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($intsallment->date)->format('F j, Y') }}
                                                    </td>
                                                    <td>{{ number_format($intsallment->amount) }}</td>
                                                    <td>{{ number_format($intsallment->paid_amount) }}</td>
                                                    <td>{{ number_format($intsallment->remaining_amount) }}</td>
                                                    <td>{{ Str::of($intsallment->status)->replace('_', ' ')->title() }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if (isset($customFields) && count($customFields) > 0)

        <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
            <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                <div class="card-body">

                    <div class="row mb-1 g-1">
                        @forelse ($customFields as $field)
                            {!! $field !!}
                        @empty
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    @endif
    <div id="comments" class="col-lg-12 col-md-12 col-sm-12 position-relative">
        <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;"
            id="stakeholders_card">
            <div class="card-header justify-content-between">
                <h3>Comments </h3>
            </div>

            <div class="card-body">

                <div class="row mb-1">
                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                        <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                            <textarea class="form-control form-control-lg" id="custom_comments" name="comments"
                                {{ isset($resale) ? 'disabled' : '' }} placeholder="Comments" rows="5">{{ isset($resale) ? $resale->comments : '' }}</textarea>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>

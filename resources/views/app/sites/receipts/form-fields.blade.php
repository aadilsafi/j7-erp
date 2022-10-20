<div data-repeater-list="receipts">
    <div data-repeater-item>

        <div class="row mb-2">
            <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                <div class="card m-0" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" style="font-size: 15px" for="unit_id">
                                        <h6 style="font-size: 15px">Unit No.</h6>
                                    </label>
                                    <select class="select2 form-select  unit_id @error('unit_id') is-invalid @enderror"
                                        name="unit_id" onclick="setIds(this)"
                                        onchange="getUnitTypeAndFloor(this.options[this.selectedIndex].value,this.id)">
                                        <option selected>Select Unit No</option>
                                        @foreach ($units as $row)
                                        @if (!$row->salesPlan->isEmpty())
                                        @continue(isset($unit) && $unit->id == $row['id'])
                                        <option value="{{ $row->id }}" {{ (isset($unit) ? $unit->parent_id :
                                            old('unit_id')) == $row['id'] ? 'selected' : '' }}>
                                            {{ $row->name }} ( {{ $row->floor_unit_number }} ) (
                                            {{ $row->salesPlan[0]['stakeholder']['full_name'] }},
                                            {{ $row->salesPlan[0]['stakeholder']['cnic'] }} )
                                        </option>
                                        @endif
                                        @endforeach
                                    </select>
                                    @error('unit_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" style="font-size: 15px" for="floor">
                                        <h6 style="font-size: 15px">Amount To be Paid</h6>
                                    </label>
                                    <input min="0" onclick="setAmountIds(this)" id="amountToBePaid" type="number"
                                        class="form-control amountToBePaid  @error('amount_in_numbers') is-invalid @enderror"
                                        name="amount_in_numbers" placeholder="Amount To be Paid"
                                        value="{{ isset($receipt) ? $receipt->amount_in_numbers : old('amount_in_numbers') }}" />
                                    @error('amount_in_numbers')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4 col-12">
                                <div class="mb-1">
                                    <label class="form-label" style="font-size: 15px" for="unit_name">
                                        <h6 style="font-size: 15px">Unit Name</h6>
                                    </label>
                                    <select disabled name="unit_name" class="select2-size-lg form-select unit_name">
                                        <option selected>Unit Name</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4 col-12">
                                <div class="mb-1">
                                    <label class="form-label" style="font-size: 15px" for="unit_type">
                                        <h6 style="font-size: 15px">Unit Type</h6>
                                    </label>
                                    <select disabled class="select2-size-lg form-select unit_type" name="unit_type">
                                        <option value="0" selected>Unit Type</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4 col-12">
                                <div class="mb-1">
                                    <label class="form-label" style="font-size: 15px" for="floor">
                                        <h6 style="font-size: 15px">Floor</h6>
                                    </label>
                                    <select name="floor" disabled class="select2-size-lg form-select floor">
                                        <option value="0" selected>Floor</option>
                                    </select>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 position-relative" id="customerData">
                <div class="card m-0" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;"
                    id="stakeholders_card">
                    <div class="card-header justify-content-between">
                        <h3> Customer Data </h3>
                    </div>

                    <div class="card-body">

                        <div class="row mb-1">
                            <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                                <label class="form-label fs-5" for="stackholder_full_name">Full Name</label>
                                <input type="text" readonly value="" class="form-control form-control-lg"
                                    id="stackholder_full_name" placeholder="Full Name" />
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                                <label class="form-label fs-5" for="stackholder_father_name">Father Name</label>
                                <input type="text" readonly value="" class="form-control form-control-lg"
                                    id="stackholder_father_name" placeholder="Father Name" />
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                                <label class="form-label fs-5" for="stackholder_occupation">Occupation</label>
                                <input type="text" readonly value="" class="form-control form-control-lg"
                                    id="stackholder_occupation" placeholder="Occupation" />
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                                <label class="form-label fs-5" for="stackholder_designation">Designation</label>
                                <input type="text" readonly value="" class="form-control form-control-lg"
                                    id="stackholder_designation" placeholder="Designation" />
                            </div>
                        </div>

                        <div class="row mb-1">

                            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                <label class="form-label fs-5" for="stackholder_ntn">NTN</label>
                                <input type="text" readonly value="" class="form-control form-control-lg"
                                    id="stackholder_ntn" placeholder="NTN" />
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                <label class="form-label fs-5" for="stackholder_cnic">CNIC</label>
                                <input type="text" readonly value="" class="form-control form-control-lg"
                                    id="stackholder_cnic" placeholder="CNIC" />
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                <label class="form-label fs-5" for="stackholder_contact">Contact</label>
                                <input type="text" readonly value="" class="form-control form-control-lg"
                                    id="stackholder_contact" placeholder="Contact" />
                            </div>
                        </div>

                        <div class="row mb-1">
                            <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                                <label class="form-label fs-5" for="stackholder_address">Address</label>
                                <textarea class="form-control  form-control-lg" readonly id="stackholder_address"
                                    placeholder="Address" rows="5"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 position-relative" id="paidInstllmentTableDiv">
                <div class="card m-0" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                    <div class="card-header justify-content-between">
                        <h3>Paid Installments</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive" style="max-height: 50rem; overflow-y: auto;">

                            <table class="table table-hover table-striped table-borderless" id="installments_table"
                                style="position: relative;">
                                <thead style="position: sticky; top: 0; z-index: 10;">
                                    <tr class="text-center text-nowrap">
                                        <th scope="col">#</th>
                                        <th scope="col">Installment No</th>
                                        <th scope="col">Total Amount</th>
                                        <th scope="col">Paid Amount</th>
                                        <th scope="col">Remaining Amount</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>

                                <tbody id="paid_dynamic_total_installment_rows">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 position-relative" id="instllmentTableDiv">
                <div class="card m-0" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                    <div class="card-header justify-content-between">
                        <h3>Unpaid Installments</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive" style="max-height: 50rem; overflow-y: auto;">

                            <table class="table table-hover table-striped table-borderless" id="installments_table"
                                style="position: relative;">
                                <thead style="position: sticky; top: 0; z-index: 10;">
                                    <tr class="text-center text-nowrap">
                                        <th scope="col">#</th>
                                        <th scope="col">Installment No</th>
                                        {{-- <th scope="col">Due Date</th> --}}
                                        <th scope="col">Total Amount</th>
                                        <th scope="col">Paid Amount</th>
                                        <th scope="col">Remaining Amount</th>
                                        <th scope="col">Partially Paid Amount</th>
                                    </tr>
                                </thead>

                                <tbody id="dynamic_total_installment_rows">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 position-relative" id="modeOfPaymentDiv">

                <div class="card m-0" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                    <div class="card-header justify-content-between">
                        <h3>Mode of Payments</h3>
                    </div>

                    <div class="card-body">

                        <div class="row custom-options-checkable mb-2 g-1">
                            <div class="col-md-3">
                                <input class="custom-option-item-check checkClass mode-of-payment" type="radio" checked
                                    name="mode_of_payment" id="customOptionsCheckableRadiosWithIcon1" value="Cash">
                                <label class="custom-option-item text-center p-1"
                                    for="customOptionsCheckableRadiosWithIcon1">
                                    {{-- <i data-feather='dollar-sign'></i> --}}
                                    <i class="bi bi-cash-coin" style="font-size: 20px"></i>
                                    <span class="custom-option-item-title h4 d-block">Cash</span>
                                </label>
                            </div>
                            <div class="col-md-3">
                                <input class="custom-option-item-check checkClass cheque-mode-of-payment" type="radio"
                                    name="mode_of_payment" id="customOptionsCheckableRadiosWithIcon2" value="Cheque">
                                <label class="custom-option-item text-center p-1"
                                    for="customOptionsCheckableRadiosWithIcon2">
                                    <i class="bi bi-bank" style="font-size: 20px"></i>
                                    <span class="custom-option-item-title h4 d-block">Cheque</span>
                                </label>
                            </div>
                            <div class="col-md-3">
                                <input class="custom-option-item-check checkClass online-mode-of-payment" type="radio"
                                    name="mode_of_payment" id="customOptionsCheckableRadiosWithIcon3" value="Online">
                                <label class="custom-option-item text-center p-1"
                                    for="customOptionsCheckableRadiosWithIcon3">
                                    <i class="bi bi-app-indicator" style="font-size: 20px"></i>
                                    <span class="custom-option-item-title h4 d-block">Online</span>
                                </label>
                            </div>
                            <div class="col-md-3">
                                <input class="custom-option-item-check other-mode-of-payment" type="radio"
                                    name="mode_of_payment" id="customOptionsCheckableRadiosWithIcon4" value="Other">
                                <label class="custom-option-item text-center text-center p-1"
                                    for="customOptionsCheckableRadiosWithIcon4">
                                    <i class="bi bi-wallet" style="font-size: 20px"></i>
                                    <span class="custom-option-item-title h4 d-block">Other</span>
                                </label>
                            </div>
                        </div>

                        <div class="row mb-2 g-1" id="otherValueDiv" style="display: none;">
                            <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                                <label class="form-label" style="font-size: 15px" for="other_value">Other Payment
                                    Mode</label>
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
                                    No</label>
                                <input type="text"
                                    class="form-control form-control-lg @error('online_instrument_no') is-invalid @enderror"
                                    id="online_instrument_no" name="online_instrument_no"
                                    placeholder="Online Transaction"
                                    value="{{ isset($receipt) ? $receipt->online_instrument_no : old('online_instrument_no') }}" />
                                @error('online_instrument_no')
                                <div class="invalid-tooltip">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 position-relative">
                                <label class="form-label" style="font-size: 15px" for="transaction_date">Transaction
                                    Date</label>
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
                            <div class="col-lg-6 col-md-6 col-sm-12 position-relative">
                                <label class="form-label" style="font-size: 15px" for="cheque_no">Cheque No</label>
                                <input type="text"
                                    class="form-control form-control-lg @error('cheque_no') is-invalid @enderror"
                                    id="cheque_no" name="cheque_no" placeholder="Cheque No"
                                    value="{{ isset($receipt) ? $receipt->cheque_no : old('cheque_no') }}" />
                                @error('cheque_no')
                                <div class="invalid-tooltip">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 position-relative">
                                <label class="form-label" style="font-size: 15px" for="bank_details">Bank Name</label>
                                <input type="text"
                                    class="form-control form-control-lg @error('bank_details') is-invalid @enderror"
                                    id="bank_details" name="bank_details" placeholder="Bank Name" />
                                @error('bank_details')
                                <div class="invalid-tooltip">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if (isset($customFields) && count($customFields) > 0)

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
        @endif

        <div class="row mb-2">
            <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                <div class="card m-0" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                    <div class="card-header justify-content-between">
                        <h3>Comments</h3>
                    </div>
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                                <label class="form-label" style="font-size: 15px" for="custom_comments">Comments</label>
                                <textarea class="form-control form-control-lg" id="custom_comments" name="comments"
                                    placeholder="Comments" rows="5"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
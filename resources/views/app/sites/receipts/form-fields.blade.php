<div data-repeater-list="receipts">
    <div  data-repeater-item class="card">
        <div class="card-body" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
            <div >
                <div class="row d-flex align-items-end">
                    <div class="col-md-6 col-12">
                        <div class="mb-1">
                            <label class="form-label" style="font-size: 15px" for="unit_id"> <h6 style="font-size: 15px">Unit No.</h6></label>
                                <select class="form-select form-select-lg unit_id @error('unit_id') is-invalid @enderror"  name="unit_id" onclick="setIds(this)" onchange="getUnitTypeAndFloor(this.options[this.selectedIndex].value,this.id)">
                                    <option value="0" selected>Select Unit No</option>
                                        @foreach ($units as $row)
                                            @if( !$row->salesPlan->isEmpty() )
                                                @continue(isset($unit) && $unit->id == $row['id'])
                                                <option value="{{ $row->id }}"
                                                    {{ (isset($unit) ? $unit->parent_id : old('unit_id')) == $row['id'] ? 'selected' : '' }}>
                                                    {{ $row->name }} ( {{ $row->floor_unit_number }} )
                                                </option>
                                            @endif
                                        @endforeach
                                </select>
                                @error('unit_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                    {{-- <span class="text-danger">{{ $message }}</span> --}}
                                @enderror
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        <div class="mb-1">
                            <label class="form-label" style="font-size: 15px" for="floor"><h6 style="font-size: 15px"> Amount To be Paid</h6></label>
                            <input onclick="setAmountIds(this)"  id="amountToBePaid" type="number" class="form-control amountToBePaid form-control-lg @error('amount_in_numbers') is-invalid @enderror"
                             name="amount_in_numbers" placeholder="Amount To be Paid"
                                value="{{ isset($receipt) ? $receipt->name : old('amount_in_numbers') }}" />
                            @error('amount_in_numbers')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4 col-12">
                        <div class="mb-1">
                            <label class="form-label" style="font-size: 15px" for="unit_name"> <h6 style="font-size: 15px">Unit Name</h6></label>
                                <select disabled class="select2-size-lg form-select unit_name"  >
                                    <option selected>Unit Name</option>
                                </select>
                        </div>
                    </div>

                    <div class="col-md-4 col-12">
                        <div class="mb-1">
                            <label class="form-label" style="font-size: 15px" for="unit_type"> <h6 style="font-size: 15px">Unit Type</h6></label>
                                <select disabled class="select2-size-lg form-select unit_type"  name="unit_type">
                                    <option value="0" selected>Unit Type</option>
                                </select>
                        </div>
                    </div>

                    <div class="col-md-4 col-12">
                        <div class="mb-1">
                            <label class="form-label" style="font-size: 15px" for="floor"> <h6 style="font-size: 15px">Floor</h6></label>
                                <select disabled class="select2-size-lg form-select floor"  >
                                    <option value="0" selected>Floor</option>
                                </select>
                        </div>
                    </div>


                    <div id="instllmentTableDiv" class=" col-lg-12 col-md-12 col-sm-12 position-relative">
                        <div class="card m-0" style="border: 2px solid #eee; border-style: dashed; border-radius: 0;">
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
                                            </tr>
                                        </thead>

                                        <tbody id="dynamic_total_installment_rows">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- <div class="col-12">
                        <div class="mb-1">
                                <h6 style="font-size: 15px">Purpose</h6>
                                <div class="row custom-options-checkable g-1">
                                  <div class="col-md-4">
                                    <input class="custom-option-item-check purpose" type="radio" name="mode-of-payment" id="customOptionsCheckableRadiosWithPurpose1" value="down_payment">
                                    <label class="custom-option-item text-center p-1" for="customOptionsCheckableRadiosWithPurpose1">
                                      <span class="custom-option-item-title h4 d-block text-nowrap">Down Payment</span>
                                    </label>
                                  </div>

                                  <div class="col-md-4">
                                    <input class="custom-option-item-check installment-purpose" type="radio" name="mode-of-payment" id="customOptionsCheckableRadiosWithPurpose2" value="Cheque">
                                    <label class="custom-option-item text-center p-1" for="customOptionsCheckableRadiosWithPurpose2">
                                      <span class="custom-option-item-title h4 d-block">Installment</span>
                                    </label>
                                  </div>

                                  <div class="col-md-4">
                                    <input class="custom-option-item-check other-purpose" type="radio" name="mode-of-payment" id="customOptionsCheckableRadiosWithPurpose3" value="Other">
                                    <label class="custom-option-item text-center text-center p-1" for="customOptionsCheckableRadiosWithPurpose3">
                                      <span class="custom-option-item-title h4 d-block">Other</span>
                                      </label>
                                  </div>

                                  <div id="installmentValueDiv" class="col-md-12 ">
                                    <input type="number" max="99" class="form-control form-control-lg @error('installment_number') is-invalid @enderror"
                                        id="installment_number" name="installment_number" placeholder="Installment No"
                                            value="{{ isset($receipt) ? $receipt->installment_number : old('installment_number') }}" />
                                        @error('installment_number')
                                            <div class="invalid-tooltip">{{ $message }}</div>
                                        @enderror
                                  </div>

                                  <div id="otherPurposeValueDiv" class="col-md-12 ">
                                    <input type="text" class="form-control form-control-lg @error('other_purpose') is-invalid @enderror"
                                        id="other_purpose" name="other_purpose" placeholder="Other Purpose"
                                            value="{{ isset($receipt) ? $receipt->other_purpose : old('other_purpose') }}" />
                                        @error('other_purpose')
                                            <div class="invalid-tooltip">{{ $message }}</div>
                                        @enderror
                                  </div>

                                </div>
                        </div>
                    </div> --}}


                    <div id="modeOfPaymentDiv" class="col-12 mt-1">
                        <div class="mb-1">
                                <h6 style="font-size: 15px">Mode Of Payment</h6>
                                <div class="row custom-options-checkable g-1">
                                  <div class="col-md-3">
                                    <input class="custom-option-item-check mode-of-payment" type="radio" name="mode_of_payment" id="customOptionsCheckableRadiosWithIcon1" value="Cash">
                                    <label class="custom-option-item text-center p-1" for="customOptionsCheckableRadiosWithIcon1">
                                      <span class="custom-option-item-title h4 d-block">Cash</span>
                                    </label>
                                  </div>

                                  <div class="col-md-3">
                                    <input class="custom-option-item-check cheque-mode-of-payment" type="radio" name="mode_of_payment" id="customOptionsCheckableRadiosWithIcon2" value="Cheque">
                                    <label class="custom-option-item text-center p-1" for="customOptionsCheckableRadiosWithIcon2">
                                      <span class="custom-option-item-title h4 d-block">Cheque</span>
                                    </label>
                                  </div>

                                  <div class="col-md-3">
                                    <input class="custom-option-item-check online-mode-of-payment" type="radio" name="mode_of_payment" id="customOptionsCheckableRadiosWithIcon3" value="Online">
                                    <label class="custom-option-item text-center p-1" for="customOptionsCheckableRadiosWithIcon3">
                                      <span class="custom-option-item-title h4 d-block">Online</span>
                                    </label>
                                  </div>

                                  <div class="col-md-3">
                                    <input class="custom-option-item-check other-mode-of-payment" type="radio" name="mode_of_payment" id="customOptionsCheckableRadiosWithIcon4" value="Other">
                                    <label class="custom-option-item text-center text-center p-1" for="customOptionsCheckableRadiosWithIcon4">
                                      <span class="custom-option-item-title h4 d-block">Other</span>
                                      </label>
                                  </div>

                                  <div id="otherValueDiv" class="col-md-12 ">
                                    <label class="form-label" style="font-size: 15px" for="floor"><h6 style="font-size: 15px">Other Payment Mode</h6></label>
                                    <input type="text" class="form-control form-control-lg @error('other_value') is-invalid @enderror"
                                        id="other_value" name="other_value" placeholder="Other Payment Mode"
                                            value="{{ isset($receipt) ? $receipt->other_value : old('other_value') }}" />
                                        @error('other_value')
                                            <div class="invalid-tooltip">{{ $message }}</div>
                                        @enderror
                                  </div>

                                  <div id="onlineValueDiv" class="col-md-12 ">
                                    <label class="form-label" style="font-size: 15px" for="floor"><h6 style="font-size: 15px">Transaction No</h6></label>
                                    <input type="text" class="form-control form-control-lg @error('online_instrument_no') is-invalid @enderror"
                                        id="online_instrument_no" name="online_instrument_no" placeholder="Online Transaction"
                                            value="{{ isset($receipt) ? $receipt->online_instrument_no : old('online_instrument_no') }}" />
                                        @error('online_instrument_no')
                                            <div class="invalid-tooltip">{{ $message }}</div>
                                        @enderror
                                  </div>

                                  <div id="chequeValueDiv" class="col-md-12 ">
                                    <label class="form-label" style="font-size: 15px" for="floor"><h6 style="font-size: 15px">Cheque No</h6></label>
                                    <input type="text" class="form-control form-control-lg @error('cheque_no') is-invalid @enderror"
                                        id="cheque_no" name="cheque_no" placeholder="Cheque No"
                                            value="{{ isset($receipt) ? $receipt->cheque_no : old('cheque_no') }}" />
                                        @error('cheque_no')
                                            <div class="invalid-tooltip">{{ $message }}</div>
                                        @enderror
                                  </div>

                                </div>
                        </div>
                    </div>

                    {{--

                    <div class="col-md-4 col-12">
                        <div class="mb-1">
                            <label class="form-label" style="font-size: 15px" for="floor"><h6 style="font-size: 15px">Pay Order</h6></label>
                            <input type="text" class="form-control form-control-lg @error('pay_order') is-invalid @enderror"
                                id="pay_order" name="pay_order" placeholder="Pay Order"
                                    value="{{ isset($receipt) ? $receipt->pay_order : old('pay_order') }}" />
                                @error('pay_order')
                                    <div class="invalid-tooltip">{{ $message }}</div>
                                @enderror
                        </div>
                    </div>

                    <div class="col-md-4 col-12">
                        <div class="mb-1">
                            <label class="form-label" style="font-size: 15px" for="floor"><h6 style="font-size: 15px">Cheque No</h6></label>
                            <input type="text" class="form-control form-control-lg @error('cheque_no') is-invalid @enderror"
                                id="cheque_no" name="cheque_no" placeholder="Cheque No"
                                    value="{{ isset($receipt) ? $receipt->cheque_no : old('cheque_no') }}" />
                                @error('cheque_no')
                                    <div class="invalid-tooltip">{{ $message }}</div>
                                @enderror
                        </div>
                    </div>

                    <div class="col-md-4 col-12">
                        <div class="mb-1">
                            <label class="form-label" style="font-size: 15px" for="floor"><h6 style="font-size: 15px">Drwan On Bank</h6></label>
                            <input type="text" class="form-control form-control-lg @error('drawn_on_bank') is-invalid @enderror"
                                id="drawn_on_bank" name="drawn_on_bank" placeholder="Drawn On Bank"
                                    value="{{ isset($receipt) ? $receipt->drawn_on_bank : old('drawn_on_bank') }}" />
                                @error('drawn_on_bank')
                                    <div class="invalid-tooltip">{{ $message }}</div>
                                @enderror
                        </div>
                    </div>

                    <div class="col-md-4 col-12">
                        <div class="mb-1">
                            <label class="form-label" style="font-size: 15px" for="floor"><h6 style="font-size: 15px">Transaction Date</h6></label>
                            <input type="text" class="form-control form-control-lg @error('transaction_date') is-invalid @enderror"
                                id="transaction_date" name="transaction_date" placeholder="Transaction Date"
                                    value="{{ isset($receipt) ? $receipt->transaction_date : old('transaction_date') }}" />
                                @error('transaction_date')
                                    <div class="invalid-tooltip">{{ $message }}</div>
                                @enderror
                        </div>
                    </div>

                    <div class="col-md-4 col-12">
                        <div class="mb-1">
                            <label class="form-label" style="font-size: 15px" for="floor"><h6 style="font-size: 15px">Transaction No</h6></label>
                            <input type="text" class="form-control form-control-lg @error('online_instrument_no') is-invalid @enderror"
                                id="online_instrument_no" name="online_instrument_no" placeholder="Online Transaction"
                                    value="{{ isset($receipt) ? $receipt->online_instrument_no : old('online_instrument_no') }}" />
                                @error('online_instrument_no')
                                    <div class="invalid-tooltip">{{ $message }}</div>
                                @enderror
                        </div>
                    </div>

                    <div class="col-md-4 col-12">
                        <div class="mb-1">
                            <label class="form-label" style="font-size: 15px" for="floor"><h6 style="font-size: 15px">Paid Amount</h6></label>
                            <input type="text" class="form-control form-control-lg @error('amount_in_numbers') is-invalid @enderror"
                                id="amount_in_numbers" name="amount_in_numbers" placeholder="Amount in Numbers"
                                    value="{{ isset($receipt) ? $receipt->online_instrument_no : old('amount_in_numbers') }}" />
                                @error('amount_in_numbers')
                                    <div class="invalid-tooltip">{{ $message }}</div>
                                @enderror
                        </div>
                    </div>--}}


                    <div class="col-md-12 col-12 mb-50 d-flex flex-row-reverse">
                        <div class="mb-1 mt-1">
                            <button class="btn btn-outline-danger text-nowrap px-1"
                                data-repeater-delete type="button">
                                <i data-feather="x" class="me-25"></i>
                                <span>Remove Above Receipt Form</span>
                            </button>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>

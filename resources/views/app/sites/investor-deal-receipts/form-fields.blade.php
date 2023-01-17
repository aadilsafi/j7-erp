<div class="row mb-2" id="divLoader">
    <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
        <div class="card m-0" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
            <div class="card-body">
                <div class="row">

                    <div class="col-md">
                        <div class="mb-1">
                            <label class="form-label" style="font-size: 15px" for="deal_id">
                                Investor Deal <span class="text-danger">*</span>
                            </label>

                            <select
                                class="select2 form-select deal_id @error('transfer_file_id') is-invalid @enderror"
                                name="deal_id" id="deal_id">
                                <option selected>Select Investor Deal</option>

                                @foreach ($investor_deals as $row)
                                    <option value="{{ $row->id }}">
                                        {{ $row->investor->full_name }} (
                                        {{ $row->investor->cnic }} , {{ $row->serial_number }} , {{ $row->doc_no }})
                                    </option>
                                @endforeach
                            </select>
                            @error('deal_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>



<div class="row mb-1 ">
    <div class="col-lg-12 col-md-12 col-sm-12 position-relative" id="modeOfPaymentDiv">

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

                {{-- Other Payment Mode Details --}}
                <div class=" mb-2 g-1" id="otherValueDiv" style="display: none;">


                    <div class="row mb-2">

                        <div class="col-lg-12 col-md-12 col-sm-12 mb-2 position-relative">
                            <label class="form-label" style="font-size: 15px" for="other_value">Other Payment
                                Purpose <span class="text-danger">*</span></label>
                            <input type="text"
                                class="form-control form-control-lg @error('other_value') is-invalid @enderror"
                                id="other_value" name="other_value" placeholder="Other Payment Purpose"
                                value="{{ isset($receipt) ? $receipt->other_value : old('other_value') }}" />
                            @error('other_value')
                                <div class="invalid-tooltip">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class=" col-sm-3 position-relative">
                            <label class="form-label" style="font-size: 15px" for="customer_ap_amount">Customer
                                Payable Amount
                                <span class="text-danger">*</span></label>
                            <input readonly type="text"
                                class="form-control form-control-lg @error('other_value') is-invalid @enderror"
                                id="customer_ap_amount" placeholder="Customer AP Amount" value="" />
                            @error('customer_ap_amount')
                                <div class="invalid-tooltip">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class=" col-sm-3 position-relative">
                            <label class="form-label" style="font-size: 15px" for="vendor_ap_amount">Vendor
                                Payable Amount
                                <span class="text-danger">*</span></label>
                            <input readonly type="text"
                                class="form-control form-control-lg @error('other_value') is-invalid @enderror"
                                id="vendor_ap_amount" placeholder="Vendor AP Amount" value="" />
                            @error('vendor_ap_amount')
                                <div class="invalid-tooltip">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class=" col-sm-3 position-relative">
                            <label class="form-label" style="font-size: 15px" for="dealer_ap_amount">Dealer
                                Payable Amount
                                <span class="text-danger">*</span></label>
                            <input readonly type="text"
                                class="form-control form-control-lg @error('other_value') is-invalid @enderror"
                                id="dealer_ap_amount" placeholder="Dealer AP Amount" value="" />
                            @error('dealer_ap_amount')
                                <div class="invalid-tooltip">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class=" col-sm-3 position-relative">
                            <label class="form-label" style="font-size: 15px" for="investor_ap_amount">Investor
                                Payable Amount
                                <span class="text-danger">*</span></label>
                            <input readonly type="text"
                                class="form-control form-control-lg @error('other_value') is-invalid @enderror"
                                id="investor_ap_amount" placeholder="Investor AP Amount" value="" />
                            @error('investor_ap_amount')
                                <div class="invalid-tooltip">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    <div class="row mb-2">

                        <div class=" col-sm-3 position-relative">
                            <label class="form-label" style="font-size: 15px" for="customer_ap_amount_paid">Paid
                                Customer Payable Amount
                                <span class="text-danger">*</span></label>
                            <input type="number"
                                class="form-control form-control-lg @error('other_value') is-invalid @enderror"
                                id="customer_ap_amount_paid" value="0" name="customer_ap_amount"
                                placeholder="Customer AP Amount" value="" />
                            @error('customer_ap_amount_paid')
                                <div class="invalid-tooltip">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class=" col-sm-3 position-relative">
                            <label class="form-label" style="font-size: 15px" for="vendor_ap_amount_paid">Paid Vendor
                                Payable Amount
                                <span class="text-danger">*</span></label>
                            <input type="number"
                                class="form-control form-control-lg @error('other_value') is-invalid @enderror"
                                id="vendor_ap_amount_paid" value="0" name="vendor_ap_amount"
                                placeholder="Vendor AP Amount" value="" />
                            @error('vendor_ap_amount_paid')
                                <div class="invalid-tooltip">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class=" col-sm-3 position-relative">
                            <label class="form-label" style="font-size: 15px" for="dealer_ap_amount_paid">Paid Dealer
                                Payable Amount
                                <span class="text-danger">*</span></label>
                            <input type="number"
                                class="form-control form-control-lg @error('other_value') is-invalid @enderror"
                                id="dealer_ap_amount_paid" value="0" name="dealer_ap_amount"
                                placeholder="Dealer AP Amount" value="" />
                            @error('dealer_ap_amount_paid')
                                <div class="invalid-tooltip">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class=" col-sm-3 position-relative">
                            <label class="form-label" style="font-size: 15px" for="investor_ap_amount_paid">Paid Investor
                                Payable Amount
                                <span class="text-danger">*</span></label>
                            <input type="number"
                                class="form-control form-control-lg @error('other_value') is-invalid @enderror"
                                id="investor_ap_amount_paid" value="0" name="investor_ap_amount"
                                placeholder="Investor AP Amount" value="" />
                            @error('investor_ap_amount_paid')
                                <div class="invalid-tooltip">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                </div>

                {{-- Online Payment Mode Details --}}
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

                {{-- Cheque Payment Mode Details --}}
                <div class="row mb-2 g-1" id="chequeValueDiv" style="display: none;">

                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                        <label class="form-label" style="font-size: 15px" for="cheque_no">Cheque No <span
                                class="text-danger">*</span></label>
                        <input type="text"
                            class="form-control form-control-lg @error('cheque_no') is-invalid @enderror"
                            id="cheque_no" name="cheque_no" placeholder="Cheque No"
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
                        <textarea class="form-control form-control-lg" id="custom_comments" name="comments" placeholder="Comments"
                            rows="5"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

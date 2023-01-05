<div class="row mb-1">
    <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
        <div class="card m-0" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
            <div class="card-body">
                <div class="row mb-1">
                    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                        <label class="form-label" style="font-size: 15px" for="unit_id">
                            Select Stakeholder <span class="text-danger">*</span>
                        </label>
                        <select id="stakeholderAP" class="select2 form-select" name="stakeholder_id">
                            <option value="">Select Stakeholder</option>
                            @foreach ($stakholders as $row)
                                <option value="{{ $row->id }}">
                                    {{ $row->full_name }} ( {{ $row->cnic }})
                                </option>
                            @endforeach
                            @error('stakeholderAP')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </select>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                        <label class="form-label" style="font-size: 15px" for="unit_id">
                            Select Stakeholder Type <span class="text-danger">*</span>
                        </label>
                        <select id="stakholder_type"
                            onchange="getAccountsPayableData(this.options[this.selectedIndex].value)"
                            class="select2 form-select" name="stakeholder_type_id">
                        </select>
                        @error('stakeholder_type_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="main-div">

    <div class="row mb-1">
        <div class="col-lg-12 col-md-12 col-sm-12 position-relative" id="customerData">
            <div class="card m-0" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;"
                id="stakeholders_card">
                <div class="card-header justify-content-between">
                    <h3> Details </h3>
                </div>

                <div class="card-body">

                    <div class="row mb-1">
                        <div class="col-lg col-md col-sm-6 position-relative">
                            <label class="form-label fs-5" for="name">Name
                                <span class="text-danger">*</span></label>
                            <input type="text" value="" readonly class="form-control form-control-md"
                                id="name" name="name" placeholder="Name" />
                        </div>
                        <div class="col-lg col-md col-sm-6 position-relative">

                            <label class="form-label fs-5" for="identity_number">Identity Number
                                <span class="text-danger">*</span></label>
                            <input type="text" name="identity_number" value="" readonly
                                class="form-control form-control-md" id="identity_number"
                                placeholder="Identity Number" />
                        </div>
                        <div class="col-lg col-md col-sm-6 position-relative">

                            <label class="form-label fs-5" for="buiness_address">Buiness Address
                                <span class="text-danger">*</span></label>
                            <input type="text" value="" name="buiness_address" readonly
                                class="form-control form-control-md" id="buiness_address"
                                placeholder="Buiness Address" />
                        </div>
                    </div>

                    <div class="row mb-1">
                        <div class="col-lg col-md col-sm-6 position-relative">
                            <label class="form-label fs-5" for="ntn">NTN</label>
                            <input type="text" value="" readonly class="form-control form-control-md"
                                id="ntn" placeholder="NTN" name="ntn" />
                        </div>
                        <div class="col-lg col-md col-sm-6 position-relative">

                            <label class="form-label fs-5" for="tax_status">Tax Status
                                <span class="text-danger">*</span></label>
                            <input type="text" value="" name="tax_status" class="form-control form-control-md"
                                id="tax_status" placeholder="Tax Status" />
                        </div>

                    </div>

                    <div class="row mb-1" id="representativeBussinessInputFields">
                        <div class="col-lg col-md col-sm-6 position-relative">

                            <label class="form-label fs-5" for="Representative">Representative
                                <span class="text-danger">*</span></label>
                            <input type="text" name="representative" value=""
                                class="form-control form-control-md" id="representative" placeholder="Representative" />
                        </div>
                        <div class="col-lg col-md col-sm-6 position-relative">
                            <label class="form-label fs-5" for="business_type">Business Type
                                <span class="text-danger">*</span></label>
                            <input type="text" value="" name="business_type"
                                class="form-control form-control-md" id="business_type"
                                placeholder="Business Type" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-1">
        <div class="col-lg-12 col-md-12 col-sm-12 position-relative" id="customerData">
            <div class="card m-0" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;"
                id="stakeholders_card">
                <div class="card-header justify-content-between">
                    <h3> Transaction Details </h3>
                </div>

                <div class="card-body">
                    <div class="row mb-1">
                        <div class="col-lg col-md col-sm-6 position-relative">

                            <label class="form-label fs-5" for="description">Description
                                <span class="text-danger">*</span></label>
                            <input type="text" value="" class="form-control form-control-md"
                                id="description" name="description" placeholder="Description" />
                        </div>
                        <div class="col-lg col-md col-sm-6 position-relative">

                            <label class="form-label fs-5" for="account_payable">Account
                                Payable
                                <span class="text-danger">*</span></label>
                            <input type="text" readonly value="" class="form-control form-control-md"
                                id="account_payable" name="account_payable" placeholder="Account Payable" />
                        </div>

                        <div class="col-lg col-md col-sm-6 position-relative">

                            <label class="form-label fs-5" for="total_payable_amount">Total
                                Payable Amount<span class="text-danger">*</span></label>
                            <input type="text" readonly value="" class="form-control form-control-md"
                                id="total_payable_amount" name="total_payable_amount"
                                placeholder="Total Payable Amount" />
                        </div>
                    </div>
                    <div class="row mb-1">

                        <div id="expanseAccountInputField" class="col-lg col-md col-sm-6 position-relative">

                            <label class="form-label fs-5" for="expense_account">Expense
                                Account
                                <span class="text-danger">*</span></label>
                            <input type="text" value="" class="form-control form-control-md"
                                id="expense_account" name="expense_account" placeholder="Expense Account" />
                        </div>
                        <div class="col-lg col-md col-sm-6 position-relative advanceDiscountInputField">

                            <label class="form-label fs-5" for="advance_given">Advance
                                Given
                                <span class="text-danger">*</span></label>
                            <input type="text" value="" class="form-control form-control-md"
                                id="advance_given" name="advance_given" placeholder="Advance Given" />
                        </div>
                        <div class="col-lg col-md col-sm-6 position-relative advanceDiscountInputField">


                            <label class="form-label fs-5" for="discount_recevied">Discount
                                Recevied
                                <span class="text-danger">*</span></label>
                            <input type="text" value="" name="discount_recevied"
                                class="form-control form-control-md" id="discount_recevied"
                                placeholder="Discount Recevied" />
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


    <div class="row mb-1">
        <div class="col-lg-12 col-md-12 col-sm-12 position-relative" id="customerData">
            <div class="card m-0" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;"
                id="stakeholders_card">
                {{-- <div class="card-header justify-content-between">
                    <h3> </h3>
                </div> --}}

                <div class="card-body">
                    <div class="row mb-1  position-relative">
                        <div class="col-lg-6 col-md-6 col-sm-6 text-nowrap">
                            <label class=" form-label fs-5" for="remaining_payable">Remaining
                                Payable
                                :</label>
                            <input type="text" readonly value="" class=" form-control form-control-md"
                                name="remaining_payable" id="remaining_payable" placeholder="Remaining Payable" />
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 text-nowrap">
                            <label class=" form-label fs-5" for="net_payable">NET
                                Payable
                                :</label>
                            <input type="text" readonly value="" class=" form-control form-control-md"
                                name="net_payable" id="net_payable" placeholder="NET Payable" />
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="row mb-1 v-div" id="paymentTermsInputs">
        <div class="col-lg-12 col-md-12 col-sm-12 position-relative" id="instllmentTableDiv">
            <div class="card m-0" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                <div class="card-header justify-content-between">
                    <h3>Payment Terms</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive" style="max-height: 50rem; overflow-y: auto;">

                        <table class="table table-hover table-striped table-borderless" id="installments_table"
                            style="position: relative;">
                            <thead style="position: sticky; top: 0; z-index: 10;">
                                <tr class="text-center text-nowrap">
                                    <th scope="col">S#</th>
                                    <th scope="col">Due Date</th>
                                    <th scope="col">Installment</th>
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
    </div>

    <div class="row mb-1">
        <div class="col-lg-12 col-md-12 col-sm-12 position-relative" id="customerData">
            <div class="card m-0" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                <div class="card-header justify-content-between">
                    <h3>Mode of Payments</h3>
                </div>

                <div class="card-body">

                    <div class="row custom-options-checkable mb-2 g-1">
                        <div class="col-md-4">
                            <input class="custom-option-item-check checkClass  mode_of_payment_value mode-of-payment"
                                type="radio" checked name="mode_of_payment"
                                id="customOptionsCheckableRadiosWithIcon1" value="Cash">
                            <label class="custom-option-item text-center p-1"
                                for="customOptionsCheckableRadiosWithIcon1">
                                {{-- <i data-feather='dollar-sign'></i> --}}
                                <i class="bi bi-cash-coin" style="font-size: 20px"></i>
                                <span class="custom-option-item-title h4 d-block">Cash</span>
                            </label>
                        </div>
                        <div class="col-md-4">
                            <input
                                class="custom-option-item-check checkClass  mode_of_payment_value cheque-mode-of-payment"
                                type="radio" name="mode_of_payment" id="customOptionsCheckableRadiosWithIcon2"
                                value="Cheque">
                            <label class="custom-option-item text-center p-1"
                                for="customOptionsCheckableRadiosWithIcon2">
                                <i class="bi bi-bank" style="font-size: 20px"></i>
                                <span class="custom-option-item-title h4 d-block">Cheque</span>
                            </label>
                        </div>
                        <div class="col-md-4">
                            <input
                                class="custom-option-item-check checkClass mode_of_payment_value online-mode-of-payment"
                                type="radio" name="mode_of_payment" id="customOptionsCheckableRadiosWithIcon3"
                                value="Online">
                            <label class="custom-option-item text-center p-1"
                                for="customOptionsCheckableRadiosWithIcon3">
                                <i class="bi bi-app-indicator" style="font-size: 20px"></i>
                                <span class="custom-option-item-title h4 d-block">Online</span>
                            </label>
                        </div>
                        {{-- <div class="col-md-3">
                            <input class="custom-option-item-check mode_of_payment_value other-mode-of-payment" type="radio"
                                name="mode_of_payment" id="customOptionsCheckableRadiosWithIcon4" value="Other">
                            <label class="custom-option-item text-center text-center p-1"
                                for="customOptionsCheckableRadiosWithIcon4">
                                <i class="bi bi-wallet" style="font-size: 20px"></i>
                                <span class="custom-option-item-title h4 d-block">Other</span>
                            </label>
                        </div> --}}
                    </div>

                    <div class="row mb-2 g-1" id="otherValueDiv" style="display: none;">
                        <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                            <label class="form-label" style="font-size: 15px" for="other_value">Other Payment
                                Mode <span class="text-danger">*</span></label>
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
                                No <span class="text-danger">*</span></label>
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

</div>

<div class="row mb-1">
    <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
        <div class="card m-0" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
            <div class="card-body">
                <div class="row mb-1">
                    <div class="col-lg col-md col-sm-6 position-relative">
                        <label class="form-label" style="font-size: 15px" for="unit_id">
                            Select Stakeholder <span class="text-danger">*</span>
                        </label>
                        <select id="stakeholderAP" class="select2 form-select" name="stakeholder_id">
                            <option value="0">Select Stakeholder</option>
                            @foreach ($stakholders as $row)
                                <option value="{{ $row->id }}">
                                    {{ $row->full_name }} ( {{ cnicFormat($row->cnic) }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg col-md col-sm-6 position-relative">
                        <label class="form-label" style="font-size: 15px" for="unit_id">
                            Select Stakeholder Type <span class="text-danger">*</span>
                        </label>
                        <select id="stakholder_type"
                            onchange="getAccountsPayableData(this.options[this.selectedIndex].value)"
                            class="select2 form-select" name="stakeholder_type_id">
                        </select>
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
                            <label class="form-label fs-5" for="ntn">NTN<span class="text-danger">*</span></label>
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
                    <h3> Tranaction Details </h3>
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
                                id="total_payable_amount" name=""total_payable_amount
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
            <div class="card m-0" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;"
                id="stakeholders_card">
                <div class="row custom-options-checkable m-2 g-1">
                    <div class="col-md-3">
                        <input class="custom-option-item-check checkClass mode-of-payment" type="radio" checked
                            name="mode_of_payment" id="customOptionsCheckableRadiosWithIcon1" value="Cash">
                        <label class="custom-option-item text-center p-1" for="customOptionsCheckableRadiosWithIcon1">
                            <i class="bi bi-cash-coin" style="font-size: 20px"></i>
                            <span class="custom-option-item-title h4 d-block">Cash</span>
                        </label>
                    </div>
                    <div class="col-md-3">
                        <input class="custom-option-item-check checkClass cheque-mode-of-payment" type="radio"
                            name="mode_of_payment" id="customOptionsCheckableRadiosWithIcon2" value="Cheque">
                        <label class="custom-option-item text-center p-1" for="customOptionsCheckableRadiosWithIcon2">
                            <i class="bi bi-bank" style="font-size: 20px"></i>
                            <span class="custom-option-item-title h4 d-block">Cheque</span>
                        </label>
                    </div>
                    <div class="col-md-3">
                        <input class="custom-option-item-check checkClass online-mode-of-payment" type="radio"
                            name="mode_of_payment" id="customOptionsCheckableRadiosWithIcon3" value="Online">
                        <label class="custom-option-item text-center p-1" for="customOptionsCheckableRadiosWithIcon3">
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
            </div>
        </div>
    </div>
</div>

{{-- <div id="main-div">

    <div class="row mb-1">
        <div class="col-lg-12 col-md-12 col-sm-12 position-relative" id="customerData">
            <div class="card m-0" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;"
                id="stakeholders_card">
                <div class="card-header justify-content-between">
                    <h3> Details </h3>
                </div>

                <div class="card-body">
                    <div class="row mb-1">
                        <label class="col-lg-4 col-md-4 col-sm-4 form-label fs-5" for="stackholder_full_name">Name
                            :</label>
                        <input type="text" value=""
                            class="col-lg-8 col-md-8 col-sm-8 form-control form-control-md" id="stackholder_full_name"
                            placeholder="Name" />
                    </div>
                    <div class="row mb-1">

                        <label class="col-lg-4 col-md-4 col-sm-4 form-label fs-5" for="Representative">Representative
                            :</label>
                        <input type="text" value=""
                            class="col-lg-8 col-md-8 col-sm-8 form-control form-control-md" id="Representative"
                            placeholder="Representative" />
                    </div>

                    <div class="row mb-1">
                        <label class="col-lg-4 col-md-4 col-sm-4 form-label fs-5" for="business_type">Business Type
                            :</label>
                        <input type="text" value=""
                            class="col-lg-8 col-md-8 col-sm-8 form-control form-control-md" id="business_type"
                            placeholder="Business Type" />
                    </div>
                    <div class="row mb-1">
                        <label class="col-lg-4 col-md-4 col-sm-4 form-label fs-5" for="identity_number">Identity Number
                            :</label>
                        <input type="text" value=""
                            class="col-lg-8 col-md-8 col-sm-8 form-control form-control-md" id="identity_number"
                            placeholder="Identity Number" />
                    </div>
                    <div class="row mb-1">
                        <label class="col-lg-4 col-md-4 col-sm-4 form-label fs-5" for="ntn">NTN:</label>
                        <input type="text" value=""
                            class="col-lg-8 col-md-8 col-sm-8 form-control form-control-md" id="ntn"
                            placeholder="NTN" />
                    </div>
                    <div class="row mb-1">

                        <label class="col-lg-4 col-md-4 col-sm-4 form-label fs-5" for="tax_status">Tax Status
                            :</label>
                        <input type="text" value=""
                            class="col-lg-8 col-md-8 col-sm-8 form-control form-control-md" id="tax_status"
                            placeholder="Tax Status" />
                    </div>
                    <div class="row mb-1">

                        <label class="col-lg-4 col-md-4 col-sm-4 form-label fs-5" for="buiness_address">Buiness Address
                            :</label>
                        <input type="text" value=""
                            class="col-lg-8 col-md-8 col-sm-8 form-control form-control-md" id="buiness_address"
                            placeholder="Buiness Address" />
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
                    <h3> Tranaction Details </h3>
                </div>

                <div class="card-body">
                    <div class="row mb-1">
                        <label class="col-lg-4 col-md-4 col-sm-4 form-label fs-5" for="description">Description
                            :</label>
                        <input type="text" value=""
                            class="col-lg-8 col-md-8 col-sm-8 form-control form-control-md" id="description"
                            placeholder="Description" />
                    </div>
                    <div class="row mb-1">

                        <label class="col-lg-4 col-md-4 col-sm-4 form-label fs-5" for="account_payable">Account
                            Payable
                            :</label>
                        <input type="text" value=""
                            class="col-lg-8 col-md-8 col-sm-8 form-control form-control-md" id="account_payable"
                            placeholder="Account Payable" />
                    </div>

                    <div class="row mb-1">
                        <label class="col-lg-4 col-md-4 col-sm-4 form-label fs-5" for="expense_account">Expense
                            Account
                            :</label>
                        <input type="text" value=""
                            class="col-lg-8 col-md-8 col-sm-8 form-control form-control-md" id="expense_account"
                            placeholder="Expense Account" />
                    </div>
                    <div class="row mb-1">
                        <label class="col-lg-4 col-md-4 col-sm-4 form-label fs-5" for="total_payable_amount">Total
                            Payable Amount:</label>
                        <input type="text" value=""
                            class="col-lg-8 col-md-8 col-sm-8 form-control form-control-md" id="total_payable_amount"
                            placeholder="Total Payable Amount" />
                    </div>
                    <div class="row mb-1 px-2 position-relative v-div">
                        <label class="col-lg-4 col-md-4 col-sm-4 form-label fs-5" for="advance_given">Advance Given
                            :</label>
                        <input type="text" value=""
                            class="col-lg-8 col-md-8 col-sm-8 form-control form-control-md" id="advance_given"
                            placeholder="Advance Given" />
                    </div>
                    <div class="row mb-1 px-2 position-relative v-div">

                        <label class="col-lg-4 col-md-4 col-sm-4 form-label fs-5" for="discount_recevied">Discount
                            Recevied
                            :</label>
                        <input type="text" value=""
                            class="col-lg-8 col-md-8 col-sm-8 form-control form-control-md" id="discount_recevied"
                            placeholder="Discount Recevied" />
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
                    <h3> </h3>
                </div>

                <div class="card-body">
                    <div class="row mb-1 px-2 position-relative">
                        <div class="col-lg-5 col-md-5 col-sm-5">

                        </div>
                        <div class="col-lg col-md col-sm row text-center">
                            <label class="col-lg-3 col-md-3 col-sm-3 form-label fs-5" for="net_payable">NET Payable
                                :</label>
                            <input type="text" value=""
                                class="col-lg-9 col-md-9 col-sm-9 form-control form-control-md" id="net_payable"
                                placeholder="NET Payable" />
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="row mb-1 v-div">
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
            <div class="card m-0" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;"
                id="stakeholders_card">
                <div class="row custom-options-checkable m-2 g-1">
                    <div class="col-md-3">
                        <input class="custom-option-item-check checkClass mode-of-payment" type="radio" checked
                            name="mode_of_payment" id="customOptionsCheckableRadiosWithIcon1" value="Cash">
                        <label class="custom-option-item text-center p-1" for="customOptionsCheckableRadiosWithIcon1">
                            <i class="bi bi-cash-coin" style="font-size: 20px"></i>
                            <span class="custom-option-item-title h4 d-block">Cash</span>
                        </label>
                    </div>
                    <div class="col-md-3">
                        <input class="custom-option-item-check checkClass cheque-mode-of-payment" type="radio"
                            name="mode_of_payment" id="customOptionsCheckableRadiosWithIcon2" value="Cheque">
                        <label class="custom-option-item text-center p-1" for="customOptionsCheckableRadiosWithIcon2">
                            <i class="bi bi-bank" style="font-size: 20px"></i>
                            <span class="custom-option-item-title h4 d-block">Cheque</span>
                        </label>
                    </div>
                    <div class="col-md-3">
                        <input class="custom-option-item-check checkClass online-mode-of-payment" type="radio"
                            name="mode_of_payment" id="customOptionsCheckableRadiosWithIcon3" value="Online">
                        <label class="custom-option-item text-center p-1" for="customOptionsCheckableRadiosWithIcon3">
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
            </div>
        </div>
    </div>
</div> --}}

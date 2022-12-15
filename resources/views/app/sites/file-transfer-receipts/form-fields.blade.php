<div class="row mb-2" id="divLoader">
    <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
        <div class="card m-0" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
            <div class="card-body">
                <div class="row">

                    <div class="col-md">
                        <div class="mb-1">
                            <label class="form-label" style="font-size: 15px" for="transfer_file_id">
                                Transfer File <span class="text-danger">*</span>
                            </label>

                            <select
                                class="select2 form-select transfer_file_id @error('transfer_file_id') is-invalid @enderror"
                                name="transfer_file_id" id="transfer_file_id">
                                <option selected>Select Transfer File</option>

                                @foreach ($units as $row)
                                    <option value="{{ $row->transferFileId }}">
                                        {{ $row->name }} ( {{ $row->floor_unit_number }} ) (
                                        {{ $row->full_name }},
                                        {{ $row->cnic }} ) -> ( {{ $row->tp_full_name }}, {{ $row->tp_cnic }})
                                    </option>
                                @endforeach
                            </select>
                            @error('transfer_file_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
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

<div id="transferOwner" class="col-lg-12 col-md-12 col-sm-12 position-relative">
    <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;"
        id="stakeholders_card">
        <div class="card-header justify-content-between">
            <h3> Transfer Owner Information</h3>
        </div>

        <div class="card-body">

            {{--  individual Form --}}
            <div id="individualForm">
                <div class="row mb-1">
                    <div class="col-lg-4 col-md-4 col-sm-12 position-relative">
                        <label class="form-label fs-5" for="transferOwner_full_name">Full Name <span
                                class="text-danger">*</span></label>
                        <input readonly type="text" class="form-control form-control-lg" id="transferOwner_full_name"
                            placeholder="Full Name" value="" />
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 position-relative">
                        <label class="form-label fs-5" for="transferOwner_father_name">Father / Husband Name
                            <span class="text-danger">*</span></label>
                        <input readonly type="text" class="form-control form-control-lg"
                            id="transferOwner_father_name" placeholder="Father / Husband Name" value="" />
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 position-relative">
                        <label class="form-label fs-5" for="transferOwner_occupation">Occupation </label>
                        <input readonly type="text" class="form-control form-control-lg"
                            id="transferOwner_occupation" placeholder="Occupation" value="" />
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-lg-4 col-md-4 col-sm-12 position-relative">
                        <label class="form-label fs-5" for="transferOwner_designation">Designation</label>
                        <input readonly type="text" class="form-control form-control-lg"
                            id="transferOwner_designation" placeholder="Designation" value="" />
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 position-relative">
                        <label class="form-label fs-5" for="transferOwner_ntn">NTN </label>
                        <input readonly type="text" class="form-control form-control-lg" id="transferOwner_ntn"
                            placeholder="NTN" value="" />
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-12 position-relative">
                        <label class="form-label fs-5" for="transferOwner_cnic">CNIC <span
                                class="text-danger">*</span></label>
                        <input readonly type="text" class="form-control form-control-lg" id="transferOwner_cnic"
                            placeholder="CNIC" value="" />
                    </div>
                </div>
            </div>

            {{-- company form --}}
            <div id="companyForm">
                <div class="row mb-1">
                    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                        <label class="form-label fs-5" for="company_name">Company Name <span
                                class="text-danger">*</span></label>
                        <input type="text" readonly class="form-control form-control-lg"
                            id="transferOwner_company_name" placeholder="Company Name" value="" />
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                        <label class="form-label fs-5" for="industry">Industry </label>
                        <input type="text" readonly class="form-control form-control-lg"
                            id="transferOwner_industry" placeholder="Industry" value="" />
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                        <label class="form-label fs-5" for="registration">Registration # <span
                                class="text-danger">*</span></label>
                        <input type="text" readonly class="cp_cnic form-control form-control-lg"
                            id="transferOwner_registration" placeholder="Registration Number" value="" />

                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                        <label class="form-label fs-5" for="ntn">NTN </label>
                        <input type="number" readonly
                            class="form-control form-control-lg @error('ntn') is-invalid @enderror"
                            id="transferOwner_ntn" placeholder="NTN Number" value="" />

                    </div>
                </div>
            </div>

            {{-- common form  --}}
            <div class="row mb-1">
                <div class="col-lg-3 col-md-3 col-sm-12 position-relative">
                    <label class="form-label fs-5" for="transferOwner_email">Email <span
                            class="text-danger">*</span></label>
                    <input type="email" readonly class="form-control form-control-md" id="transferOwner_email"
                        placeholder="Email" autocomplete="false" value="" />

                </div>

                <div class="col-lg-3 col-md-3 col-sm-12 position-relative">
                    <label class="form-label fs-5" for="transferOwner_optional_email">Optional Email</label>
                    <input type="email" readonly class="form-control form-control-md"
                        id="transferOwner_optional_email" placeholder="Optional Email" autocomplete="false"
                        value="" />
                </div>

                <div class="col-lg-3 col-md-3 col-sm-12 position-relative">
                    <label class="form-label fs-5" for="transferOwner_contact">Contact <span
                            class="text-danger">*</span></label>
                    <input readonly type="text" class="form-control form-control-lg" id="transferOwner_contact"
                        placeholder="Contact" value="" />
                </div>
                <div class="col-lg-3 col-md-3 col-sm-12">
                    <label class="form-label fs-5" for="transferOwner_optional_contact">Optional Contact <span
                            class="text-danger">*</span></label>
                    <input readonly type="text" class="form-control form-control-lg"
                        id="transferOwner_optional_contact" placeholder="Optional Contact" value="" />
                </div>
            </div>

            <div class="row mb-1">
                <div class="col-lg-3 col-md-3 col-sm-6 position-relative">
                    <label class="form-label" style="font-size: 15px" for="parent_id">Country</label>
                    <input readonly type="text" class="form-control form-control-lg" id="transferOwner_country"
                        placeholder="Country" value="" />

                </div>

                <div class="col-lg-3 col-md-3 col-sm-6 position-relative">
                    <label class="form-label" style="font-size: 15px" for="city_id">State</label>
                    <input readonly type="text" class="form-control form-control-lg" id="transferOwner_state"
                        placeholder="State" value="" />

                </div>

                <div class="col-lg-3 col-md-3 col-sm-6 position-relative">
                    <label class="form-label" style="font-size: 15px" for="city_id">City</label>
                    <input readonly type="text" class="form-control form-control-lg" id="transferOwner_city"
                        placeholder="City" value="" />

                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 position-relative">
                    <label class="form-label fs-5" for="transferOwner_nationality">Nationality </label>
                    <input type="text" readonly
                        class="form-control form-control-lg @error('occupation') is-invalid @enderror"
                        id="transferOwner_nationality" placeholder="Nationality" value="" />

                </div>
            </div>
            <div class="row mb-1">
                <div class="col-lg-6 col-md-6 col-sm-12 position-relative">
                    <label class="form-label fs-5" for="transferOwner_address">Address <span
                            class="text-danger">*</span></label>
                    <textarea readonly class="form-control form-control-lg" id="transferOwner_address" placeholder="Address"
                        rows="3">  </textarea>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 position-relative">
                    <label class="form-label fs-5" for="transferOwner_mailing_address">Mailing Address <span
                            class="text-danger">*</span>
                    </label>
                    <textarea readonly class="form-control form-control-lg" id="transferOwner_mailing_address"
                        placeholder="Mailing Address" rows="3"></textarea>
                </div>
            </div>
            <div class="row mb-1">
                <div class="col-lg- col-md- col-sm-12 position-relative">
                    <label class="form-label fs-5" for="transferOwner_comments">Comments</label>
                    <textarea readonly class="form-control form-control-lg" id="transferOwner_comments" placeholder="Comments"
                        rows="3"></textarea>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="fileOwner" class="col-lg-12 col-md-12 col-sm-12 position-relative">
    <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;"
        id="stakeholders_card">
        <div class="card-header justify-content-between">
            <h3> File Owner Information</h3>
        </div>

        <div class="card-body">

            {{--  individual Form --}}
            <div id="OwnerIndividualForm">
                <div class="row mb-1">
                    <div class="col-lg-4 col-md-4 col-sm-12 position-relative">
                        <label class="form-label fs-5" for="fileOwner_full_name">Full Name <span
                                class="text-danger">*</span></label>
                        <input readonly type="text" class="form-control form-control-lg" id="fileOwner_full_name"
                            placeholder="Full Name" value="" />
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 position-relative">
                        <label class="form-label fs-5" for="fileOwner_father_name">Father / Husband Name
                            <span class="text-danger">*</span></label>
                        <input readonly type="text" class="form-control form-control-lg"
                            id="fileOwner_father_name" placeholder="Father / Husband Name" value="" />
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 position-relative">
                        <label class="form-label fs-5" for="fileOwner_occupation">Occupation </label>
                        <input readonly type="text" class="form-control form-control-lg" id="fileOwner_occupation"
                            placeholder="Occupation" value="" />
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-lg-4 col-md-4 col-sm-12 position-relative">
                        <label class="form-label fs-5" for="fileOwner_designation">Designation</label>
                        <input readonly type="text" class="form-control form-control-lg"
                            id="fileOwner_designation" placeholder="Designation" value="" />
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 position-relative">
                        <label class="form-label fs-5" for="fileOwner_ntn">NTN </label>
                        <input readonly type="text" class="form-control form-control-lg" id="fileOwner_ntn"
                            placeholder="NTN" value="" />
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-12 position-relative">
                        <label class="form-label fs-5" for="fileOwner_cnic">CNIC <span
                                class="text-danger">*</span></label>
                        <input readonly type="text" class="form-control form-control-lg" id="fileOwner_cnic"
                            placeholder="CNIC" value="" />
                    </div>
                </div>
            </div>

            {{-- company form --}}
            <div id="OwnerCompanyForm">
                <div class="row mb-1">
                    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                        <label class="form-label fs-5" for="company_name">Company Name <span
                                class="text-danger">*</span></label>
                        <input type="text" readonly class="form-control form-control-lg"
                            id="fileOwner_company_name" placeholder="Company Name" value="" />
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                        <label class="form-label fs-5" for="industry">Industry </label>
                        <input type="text" readonly class="form-control form-control-lg" id="fileOwner_industry"
                            placeholder="Industry" value="" />
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                        <label class="form-label fs-5" for="registration">Registration # <span
                                class="text-danger">*</span></label>
                        <input type="text" readonly class="cp_cnic form-control form-control-lg"
                            id="fileOwner_registration" placeholder="Registration Number" value="" />
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                        <label class="form-label fs-5" for="ntn">NTN </label>
                        <input type="number" readonly
                            class="form-control form-control-lg @error('ntn') is-invalid @enderror" id="fileOwner_ntn"
                            placeholder="NTN Number" value="" />
                    </div>
                </div>
            </div>

            {{-- common form  --}}
            <div class="row mb-1">
                <div class="col-lg-3 col-md-3 col-sm-12 position-relative">
                    <label class="form-label fs-5" for="fileOwner_email">Email <span
                            class="text-danger">*</span></label>
                    <input type="email" readonly class="form-control form-control-md" id="fileOwner_email"
                        placeholder="Email" autocomplete="false" value="" />

                </div>

                <div class="col-lg-3 col-md-3 col-sm-12 position-relative">
                    <label class="form-label fs-5" for="fileOwner_optional_email">Optional Email</label>
                    <input type="email" readonly class="form-control form-control-md" id="fileOwner_optional_email"
                        placeholder="Optional Email" autocomplete="false" value="" />
                </div>

                <div class="col-lg-3 col-md-3 col-sm-12 position-relative">
                    <label class="form-label fs-5" for="fileOwner_contact">Contact <span
                            class="text-danger">*</span></label>
                    <input readonly type="text" class="form-control form-control-lg" id="fileOwner_contact"
                        placeholder="Contact" value="" />
                </div>
                <div class="col-lg-3 col-md-3 col-sm-12">
                    <label class="form-label fs-5" for="fileOwner_optional_contact">Optional Contact <span
                            class="text-danger">*</span></label>
                    <input readonly type="text" class="form-control form-control-lg"
                        id="fileOwner_optional_contact" placeholder="Optional Contact" value="" />
                </div>
            </div>

            <div class="row mb-1">
                <div class="col-lg-3 col-md-3 col-sm-6 position-relative">
                    <label class="form-label" style="font-size: 15px" for="parent_id">Country</label>
                    <input readonly type="text" class="form-control form-control-lg" id="fileOwner_country"
                        placeholder="Country" value="" />

                </div>

                <div class="col-lg-3 col-md-3 col-sm-6 position-relative">
                    <label class="form-label" style="font-size: 15px" for="city_id">State</label>
                    <input readonly type="text" class="form-control form-control-lg" id="fileOwner_state"
                        placeholder="State" value="" />

                </div>

                <div class="col-lg-3 col-md-3 col-sm-6 position-relative">
                    <label class="form-label" style="font-size: 15px" for="city_id">City</label>
                    <input readonly type="text" class="form-control form-control-lg" id="fileOwner_city"
                        placeholder="City" value="" />

                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 position-relative">
                    <label class="form-label fs-5" for="fileOwner_nationality">Nationality </label>
                    <input type="text" readonly
                        class="form-control form-control-lg @error('occupation') is-invalid @enderror"
                        id="fileOwner_nationality" placeholder="Nationality" value="" />

                </div>
            </div>
            <div class="row mb-1">
                <div class="col-lg-6 col-md-6 col-sm-12 position-relative">
                    <label class="form-label fs-5" for="fileOwner_address">Address <span
                            class="text-danger">*</span></label>
                    <textarea readonly class="form-control form-control-lg" id="fileOwner_address" placeholder="Address" rows="3">  </textarea>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 position-relative">
                    <label class="form-label fs-5" for="fileOwner_mailing_address">Mailing Address <span
                            class="text-danger">*</span>
                    </label>
                    <textarea readonly class="form-control form-control-lg" id="fileOwner_mailing_address" placeholder="Mailing Address"
                        rows="3"></textarea>
                </div>
            </div>
            <div class="row mb-1">
                <div class="col-lg- col-md- col-sm-12 position-relative">
                    <label class="form-label fs-5" for="fileOwner_comments">Comments</label>
                    <textarea readonly class="form-control form-control-lg" id="fileOwner_comments" placeholder="Comments"
                        rows="3"></textarea>
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
                    <div class="col-md-3">
                        <input class="custom-option-item-check checkClass mode-of-payment" type="radio" checked
                            name="mode_of_payment" id="customOptionsCheckableRadiosWithIcon1" value="Cash">
                        <label class="custom-option-item text-center p-1" for="customOptionsCheckableRadiosWithIcon1">
                            {{-- <i data-feather='dollar-sign'></i> --}}
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

                        <div class=" col-sm-4 position-relative">
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

                        <div class=" col-sm-4 position-relative">
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

                        <div class=" col-sm-4 position-relative">
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

                    </div>

                    <div class="row mb-2">

                        <div class=" col-sm-4 position-relative">
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

                        <div class=" col-sm-4 position-relative">
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

                        <div class=" col-sm-4 position-relative">
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

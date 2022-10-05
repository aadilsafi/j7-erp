<div class="row d-flex align-items-end">

    <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
        <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;"
            id="stakeholders_card">
            <div class="card-header justify-content-between">
                <h3> Required Data </h3>
            </div>

            <div class="card-body">
                <div class="row mb-1">
                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                        <label class="form-label fs-5" for="amount_to_be_refunded">Profit Charges</label>
                        <input type="number" min="1" onchange="calculateRefundedAmount()" required
                            name="amount_profit" class="form-control form-control-lg"
                            {{ isset($buy_back_file) ? 'disabled' : '' }} id="profit_charges"
                            placeholder=" Profit Charges"
                            value="{{ isset($buy_back_file) ? number_format($buy_back_file->amount_profit) : '' }}" />
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                        <label class="form-label fs-5" for="payment_due_date">Payment Due Date</label>
                        <input type="date" required name="payment_due_date" class="form-control form-control-lg"
                            {{ isset($buy_back_file) ? 'disabled' : '' }} id="payment_due_date"
                            placeholder="Payment Due Date"
                            value="{{ isset($buy_back_file) ? $buy_back_file->payment_due_date : '' }}" />
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                        <label class="form-label fs-5" for="stackholder_father_name">Amount Remarks</label>
                        <input type="text" name="amount_remarks" required class="form-control form-control-lg"
                            id="remarks" {{ isset($buy_back_file) ? 'disabled' : '' }} placeholder="Amount Remarks"
                            value="{{ isset($buy_back_file) ? $buy_back_file->amount_remarks : '' }}" />
                    </div>
                </div>

                <div class="row mb-1">
                    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                        <label class="form-label fs-5" for="amount_to_be_refunded">Paid Amount</label>
                        <input type="text" disabled required name="paid_amount" class="form-control form-control-lg"
                            id="paid_amount" placeholder=" Paid Amount"
                            value="{{ isset($total_paid_amount) ? number_format($total_paid_amount) : '' }}" />
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                        <label class="form-label fs-5" for="amount_to_be_refunded">Amount To Be Refunded</label>
                        <input type="text" readonly required name="amount_to_be_refunded"
                            class="form-control form-control-lg" {{ isset($buy_back_file) ? 'disabled' : '' }}
                            id="amount_to_be_refunded" placeholder="Amount to be refunded"
                            value="{{ isset($buy_back_file) ? number_format($buy_back_file->amount_to_be_refunded) : '' }}" />
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div id="sellerInformation" class="col-lg-12 col-md-12 col-sm-12 position-relative">
        <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;"
            id="">
            <input type="hidden" value="{{ $customer->id }}" name="customer_id">
            <div class="card-header justify-content-between">
                <h3> Seller Information </h3>
            </div>

            <div class="card-body">

                <div class="row mb-1">
                    <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                        <label class="form-label fs-5" for="stackholder_full_name">Full Name</label>
                        <input type="text" readonly value="{{ $customer->full_name }}"
                            class="form-control form-control-lg" id="" placeholder="Full Name" />
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                        <label class="form-label fs-5" for="stackholder_father_name">Father Name</label>
                        <input type="text" readonly value="{{ $customer->father_name }}"
                            class="form-control form-control-lg" id=""
                            placeholder="Father Name" />
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                        <label class="form-label fs-5" for="stackholder_occupation">Occupation</label>
                        <input type="text" readonly value="{{ $customer->occupation }}"
                            class="form-control form-control-lg" id="" placeholder="Occupation" />
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                        <label class="form-label fs-5" for="stackholder_designation">Designation</label>
                        <input type="text" readonly value="{{ $customer->designation }}"
                            class="form-control form-control-lg" id=""
                            placeholder="Designation" />
                    </div>
                </div>

                <div class="row mb-1">

                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                        <label class="form-label fs-5" for="stackholder_ntn">NTN</label>
                        <input type="text" readonly value="{{ $customer->ntn }}"
                            class="form-control form-control-lg" id="" placeholder="NTN" />
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                        <label class="form-label fs-5" for="stackholder_cnic">CNIC</label>
                        <input type="text" readonly value="{{ cnicFormat($customer->cnic) }}"
                            class="form-control form-control-lg" id="" placeholder="CNIC" />
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                        <label class="form-label fs-5" for="stackholder_contact">Contact</label>
                        <input type="text" readonly value="{{ $customer->contact }}"
                            class="form-control form-control-lg" id="" placeholder="Contact" />
                    </div>
                </div>

                <div class="row mb-1">
                    <div class="col-lg-6 col-md-6 col-sm-12 position-relative">
                        <label class="form-label fs-5" for="stackholder_address">Address</label>
                        <textarea class="form-control  form-control-lg" readonly id="" name="stackholder[address]"
                            placeholder="Address" rows="5">{{ $customer->address }}</textarea>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-12 position-relative">
                        <label class="form-label fs-5" for="stackholder_comments">Comments</label>
                        <textarea class="form-control form-control-lg" readonly id="" name="stackholder[comments]"
                            placeholder="Address" rows="5">{{ $customer->comments }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="buyerInformaton" class="col-lg-12 col-md-12 col-sm-12 position-relative">
        <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;"
            id="stakeholders_card">
            <div class="card-header justify-content-between">
                <h3> Buyer Information</h3>
                {{-- <div id="div_stakeholder_type">
                    @forelse ($stakeholderTypes as $stakeholderType)
                        <p class="badge badge-light-danger fs-5 ms-auto me-1">{{ $stakeholderType }}-000</p>
                    @empty
                    @endforelse
                </div> --}}
            </div>

            <div class="card-body">

                <div class="row mb-1">
                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                        <label class="form-label" style="font-size: 15px" for="stackholders">Stakeholders</label>
                        <select class="form-select" id="stackholders" name="stackholder[stackholder_id]">
                            <option value="0">Create new Stakeholder...</option>
                            @forelse ($stakeholders as $stakeholder)
                                <option value="{{ $stakeholder->id }}">{{ $stakeholder->full_name }} s/o
                                    {{ $stakeholder->father_name }} {{ $stakeholder->cnic }},
                                    {{ $stakeholder->contact }}
                                </option>
                            @empty
                            @endforelse
                        </select>
                    </div>
                </div>

                <div class="row mb-1">
                    {{-- <input type="hidden" id="stackholder_id" name="stackholder[stackholder_id]" value="0" /> --}}
                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                        <label class="form-label fs-5" for="stackholder_full_name">Full Name</label>
                        <input type="text" class="form-control form-control-lg" id="stackholder_full_name"
                            name="stackholder[full_name]" placeholder="Full Name" />
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                        <label class="form-label fs-5" for="stackholder_father_name">Father Name</label>
                        <input type="text" class="form-control form-control-lg" id="stackholder_father_name"
                            name="stackholder[father_name]" placeholder="Father Name" />
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                        <label class="form-label fs-5" for="stackholder_occupation">Occupation</label>
                        <input type="text" class="form-control form-control-lg" id="stackholder_occupation"
                            name="stackholder[occupation]" placeholder="Occupation" />
                    </div>
                </div>

                <div class="row mb-1">

                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                        <label class="form-label fs-5" for="stackholder_designation">Designation</label>
                        <input type="text" class="form-control form-control-lg" id="stackholder_designation"
                            name="stackholder[designation]" placeholder="Designation" />
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
    </div>


    <div class="col-lg-12 col-md-12 col-sm-12 position-relative">

        <div class="card" id="additional_expense_card"
            style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
            <div class="card-header">
                <h3>Attachments</h3>
            </div>
            <div class="card-body">
                @if (isset($buy_back_file))
                    @foreach ($labels as $key => $label)
                        <div class="card m-0">
                            <div class="card-body">
                                <div>
                                    <div class="row mb-1">
                                        <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                                            <label class="form-label fs-5" for="expense_label">Attachement
                                                label</label>
                                            <input type="text" class="form-control form-control-lg"
                                                id="expense_label" name="attachments[attachment_label]"
                                                value="{{ $label->label }}" disabled
                                                placeholder="Attachment Label" />
                                        </div>

                                        <div class="col-lg-12 col-md-12 col-sm-12 position-relative mt-1">
                                            <label class="form-label fs-5" for="type_name">Attachment</label>
                                            <input id="attachment" type="file" class="filepond attachment"
                                                disabled name="attachment[image]"
                                                accept="image/png, image/jpeg, image/gif" />
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="expenses-list">

                        <div data-repeater-list="attachments">
                            <div data-repeater-item>
                                <div class="card m-0">
                                    <div class="card-body">
                                        <div>
                                            <div class="row mb-1">
                                                <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                                                    <label class="form-label fs-5" for="expense_label">Attachement
                                                        label</label>
                                                    <input type="text"
                                                        class="form-control form-control-lg @error('attachments') is-invalid @enderror"
                                                        id="expense_label" name="attachments[attachment_label]"
                                                        placeholder="Attachment Label" />
                                                </div>

                                                <div class="col-lg-12 col-md-12 col-sm-12 position-relative mt-1">
                                                    <label class="form-label fs-5" for="type_name">Attachment</label>
                                                    <input id="attachment" type="file"
                                                        class="filepond attachment @error('image') is-invalid @enderror"
                                                        name="attachment[image]"
                                                        accept="image/png, image/jpeg, image/gif" />
                                                    @error('image')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="row mb-1">
                                                <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                                                    <div class="d-flex h-100 justify-content-end align-items-end">
                                                        <div>
                                                            <button
                                                                class="btn btn-relief-outline-danger waves-effect waves-float waves-light"
                                                                data-repeater-delete id="delete-contact-person"
                                                                type="button">
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
                                    id="add-new-attachment" type="button" data-repeater-create>
                                    <i data-feather="plus" class="me-25"></i>
                                    <span>Add New</span>
                                </button>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>



    <div id="unitData" class="col-lg-12 col-md-12 col-sm-12 position-relative">
        <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;"
            id="stakeholders_card">
            <input type="hidden" value="{{ $unit->id }}" name="unit_id">
            <div class="card-header justify-content-between">
                <h3> Unit Data </h3>
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
                                {{ isset($buy_back_file) ? 'disabled' : '' }} placeholder="Comments" rows="5">{{ isset($buy_back_file) ? $buy_back_file->comments : '' }}</textarea>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>

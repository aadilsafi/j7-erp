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
                        <label class="form-label fs-5" for="amount_to_be_refunded">Amount To Be Refunded</label>
                        <input type="text" required name="amount_to_be_refunded" class="form-control form-control-lg"
                            id="amount_to_be_refunded" placeholder="Amount to be refunded" />
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                        <label class="form-label fs-5" for="payment_due_date">Payment Due Date</label>
                        <input type="date" required name="payment_due_date" class="form-control form-control-lg"
                            id="payment_due_date" placeholder="Payment Due Date" />
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                        <label class="form-label fs-5" for="stackholder_father_name">Amount Remarks</label>
                        <input type="text" name="amount_remarks" required class="form-control form-control-lg" id="remarks"
                            placeholder="Amount Remarks" />
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
                                                    name="attachment['image']"
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
            </div>
        </div>
    </div>

    <div id="customerData" class="col-lg-12 col-md-12 col-sm-12 position-relative">
        <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;"
            id="stakeholders_card">
            <input type="hidden" value="{{ $customer->id }}" name="customer_id">
            <div class="card-header justify-content-between">
                <h3> Customer Data </h3>
            </div>

            <div class="card-body">

                <div class="row mb-1">
                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                        <label class="form-label fs-5" for="stackholder_full_name">Full Name</label>
                        <input type="text" readonly value="{{ $customer->full_name }}"
                            class="form-control form-control-lg" id="stackholder_full_name" placeholder="Full Name" />
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                        <label class="form-label fs-5" for="stackholder_father_name">Father Name</label>
                        <input type="text" readonly value="{{ $customer->father_name }}"
                            class="form-control form-control-lg" id="stackholder_father_name"
                            placeholder="Father Name" />
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                        <label class="form-label fs-5" for="stackholder_occupation">Occupation</label>
                        <input type="text" readonly value="{{ $customer->occupation }}"
                            class="form-control form-control-lg" id="stackholder_occupation"
                            placeholder="Occupation" />
                    </div>
                </div>

                <div class="row mb-1">

                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                        <label class="form-label fs-5" for="stackholder_designation">Designation</label>
                        <input type="text" readonly value="{{ $customer->designation }}"
                            class="form-control form-control-lg" id="stackholder_designation"
                            placeholder="Designation" />
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                        <label class="form-label fs-5" for="stackholder_cnic">CNIC</label>
                        <input type="text" readonly value="{{ cnicFormat($customer->cnic) }}"
                            class="form-control form-control-lg" id="stackholder_cnic" placeholder="CNIC" />
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                        <label class="form-label fs-5" for="stackholder_contact">Contact</label>
                        <input type="text" readonly value="{{ $customer->contact }}"
                            class="form-control form-control-lg" id="stackholder_contact" placeholder="Contact" />
                    </div>
                </div>

                <div class="row mb-1">
                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                        <label class="form-label fs-5" for="stackholder_address">Address</label>
                        <textarea class="form-control  form-control-lg" readonly id="stackholder_address" placeholder="Address"
                            rows="5">{{ $customer->address }}</textarea>
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

                {{-- <div class="row mb-1">

                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                        <label class="form-label fs-5" for="stackholder_occupation">Floor Name</label>
                        <input type="text" readonly value="{{$unit->floor->name }}" class="form-control form-control-lg"
                            id="stackholder_occupation" placeholder="Unit No" />
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                        <label class="form-label fs-5" for="stackholder_full_name">Short Label</label>
                        <input type="text" readonly value="{{ $unit->floor->short_label }}" class="form-control form-control-lg"
                            id="stackholder_full_name" placeholder="Unit Name" />
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                        <label class="form-label fs-5" for="stackholder_father_name">Unit Status</label>
                        <input type="text" readonly value="{{ $unit->status->name }}" class="form-control form-control-lg"
                            id="stackholder_father_name" placeholder="Unit Type" />
                    </div>

                </div> --}}

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
                            <textarea class="form-control form-control-lg" id="custom_comments" name="comments" placeholder="Comments"
                                rows="5"></textarea>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>

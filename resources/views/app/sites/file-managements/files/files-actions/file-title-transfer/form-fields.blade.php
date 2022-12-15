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
                        <label class="form-label fs-5" for="amount_to_be_refunded">Transfer Charges <span
                                class="text-danger">*</span></label>
                        <input type="text" min="1" onchange="calculateTransferAmount()" required
                            name="transfer_rate" class="form-control form-control-lg"
                            {{ isset($transfer_file) ? 'disabled' : '' }} id="transfer_rate"
                            placeholder="Transfer Charges"
                            value="{{ isset($transfer_file) ? number_format($transfer_file->transfer_rate) : '' }}" />
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                        <label class="form-label fs-5" for="payment_due_date">Payment Due Date <span
                                class="text-danger">*</span></label>
                        <input type="date" required name="payment_due_date" class="form-control form-control-lg"
                            {{ isset($transfer_file) ? 'disabled' : '' }} id="payment_due_date"
                            placeholder="Payment Due Date"
                            value="{{ isset($transfer_file) ? $transfer_file->payment_due_date : '' }}" />
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                        <label class="form-label fs-5" for="stackholder_father_name">Transfer Amount Remarks <span
                                class="text-danger">*</span></label>
                        <input type="text" name="amount_remarks" required class="form-control form-control-lg"
                            id="remarks" {{ isset($transfer_file) ? 'disabled' : '' }}
                            placeholder="Transfer Amount Remarks"
                            value="{{ isset($transfer_file) ? $transfer_file->amount_remarks : '' }}" />
                    </div>
                </div>

                <div class="row mb-1">
                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                        <label class="form-label fs-5" for="amount_to_be_refunded">Paid Amount</label>
                        <input type="text" disabled required name="paid_amount" class="form-control form-control-lg"
                            id="paid_amount" placeholder=" Paid Amount"
                            value="{{ isset($total_paid_amount) ? number_format($total_paid_amount) : '' }}" />
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                        <label class="form-label fs-5" for="amount_to_be_refunded">Unit Area</label>
                        <input type="text" disabled required name="unit_area" class="form-control form-control-lg"
                            id="unit_area" placeholder=" Unit Area" value="{{ number_format($unit->gross_area) }} " />
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                        <label class="form-label fs-5" for="amount_to_be_refunded">Transfer Charges To Be Paid </label>
                        <input type="text" readonly required name="amount_to_be_paid"
                            class="form-control form-control-lg" id="amount_to_be_paid" placeholder="Amount to be Paid"
                            value="{{ isset($transfer_file) ? number_format($transfer_file->amount_to_be_paid) : '' }}" />
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
                @if (isset($transfer_file))
                    <div class="row mb-1 g-1">
                        @foreach ($labels as $key => $label)
                            <div class="col-lg-4 col-md-4 col-sm-12 position-relative">
                                <div class="row mb-1">
                                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                                        <label class="form-label fs-5" for="expense_label">Attachment
                                            label</label>
                                        <input type="text" class="form-control form-control-lg" id="expense_label"
                                            name="attachments[attachment_label]" value="{{ $label->label }}" disabled
                                            placeholder="Attachment Label" />
                                    </div>

                                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative mt-1">
                                        <label class="form-label fs-5" for="type_name">Attachment</label>
                                        <input id="attachment" type="file" class="filepond attachment" disabled
                                            name="attachment[image]" accept="image/png, image/jpeg, image/gif" />
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="expenses-list">

                        <div data-repeater-list="attachments">
                            <div data-repeater-item>
                                <div class="card m-0">
                                    <div class="card-body pb-0">
                                        <div>
                                            <div class="row mb-1">
                                                <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                                                    <label class="form-label fs-5" for="expense_label">Attachment
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
                                <hr>
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

    <div id="titleTransferPersonInformaton" class="col-lg-12 col-md-12 col-sm-12 position-relative">
        <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;"
            id="stakeholders_card">
            <div class="card-header justify-content-between">
                <h3> Transfer Owner Information</h3>
                {{-- <div id="div_stakeholder_type">
                    @forelse ($stakeholderTypes as $stakeholderType)
                        <p class="badge badge-light-danger fs-5 ms-auto me-1">{{ $stakeholderType }}-000</p>
                    @empty
                    @endforelse
                </div> --}}
            </div>

            <div class="card-body">
                @if (isset($stakeholders))
                    <div class="row mb-1">
                        <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                            <label class="form-label" style="font-size: 15px" for="stackholders">Stakeholders <span
                                    class="text-danger">*</span></label>
                            <select class="form-select" id="stackholders" name="stackholder[stackholder_id]">
                                <option value="0">Create new Stakeholder...</option>
                                @forelse ($stakeholders as $stakeholder)
                                    @if ($customer->id == $stakeholder->id)
                                        @continue;
                                    @endif
                                    <option value="{{ $stakeholder->id }}">{{ $stakeholder->full_name }} s/o
                                        {{ $stakeholder->father_name }} {{ $stakeholder->cnic }},
                                        {{ $stakeholder->contact }}
                                    </option>
                                @empty
                                @endforelse
                            </select>
                        </div>
                    </div>
                @endif

                {{--  individual Form --}}
                <div id="individualForm">
                    <div class="row mb-1">
                        <div class="col-lg-4 col-md-4 col-sm-12 position-relative">
                            <label class="form-label fs-5" for="stackholder_full_name">Full Name <span
                                    class="text-danger">*</span></label>
                            <input @if (isset($titleTransferPerson)) disabled @endif type="text"
                                class="form-control form-control-lg" id="stackholder_full_name"
                                name="stackholder[full_name]" placeholder="Full Name"
                                value="{{ isset($titleTransferPerson) ? $titleTransferPerson->full_name : '' }}" />
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 position-relative">
                            <label class="form-label fs-5" for="stackholder_father_name">Father / Husband Name
                                <span class="text-danger">*</span></label>
                            <input @if (isset($titleTransferPerson)) disabled @endif type="text"
                                class="form-control form-control-lg" id="stackholder_father_name"
                                name="stackholder[father_name]" placeholder="Father / Husband Name"
                                value="{{ isset($titleTransferPerson) ? $titleTransferPerson->father_name : '' }}" />
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 position-relative">
                            <label class="form-label fs-5" for="stackholder_occupation">Occupation </label>
                            <input @if (isset($titleTransferPerson)) disabled @endif type="text"
                                class="form-control form-control-lg" id="stackholder_occupation"
                                name="stackholder[occupation]" placeholder="Occupation"
                                value="{{ isset($titleTransferPerson) ? $titleTransferPerson->occupation : '' }}" />
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-lg-4 col-md-4 col-sm-12 position-relative">
                            <label class="form-label fs-5" for="stackholder_designation">Designation</label>
                            <input @if (isset($titleTransferPerson)) disabled @endif type="text"
                                class="form-control form-control-lg" id="stackholder_designation"
                                name="stackholder[designation]" placeholder="Designation"
                                value="{{ isset($titleTransferPerson) ? $titleTransferPerson->designation : '' }}" />
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 position-relative">
                            <label class="form-label fs-5" for="stackholder_ntn">NTN </label>
                            <input @if (isset($titleTransferPerson)) disabled   @else type="text" @endif
                                name="stackholder[ntn]" class="form-control form-control-lg" id="stackholder_ntn"
                                placeholder="NTN"
                                value="{{ isset($titleTransferPerson) ? $titleTransferPerson->ntn : '' }}" />
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-12 position-relative">
                            <label class="form-label fs-5" for="stackholder_cnic">CNIC <span
                                    class="text-danger">*</span></label>
                            <input @if (isset($titleTransferPerson)) disabled @endif type="text"
                                class="form-control form-control-lg" id="stackholder_cnic" name="stackholder[cnic]"
                                placeholder="CNIC"
                                value="{{ isset($titleTransferPerson) ? cnicFormat($titleTransferPerson->cnic) : '' }}" />
                        </div>
                    </div>
                </div>


                {{-- company form --}}
                <div id="companyForm">
                    <div class="row mb-1">
                        <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                            <label class="form-label fs-5" for="company_name">Company Name <span
                                    class="text-danger">*</span></label>
                            <input type="text" @if (isset($titleTransferPerson)) disabled @endif
                                class="form-control form-control-lg @error('full_name') is-invalid @enderror"
                                id="company_name" placeholder="Company Name"
                                value="{{ isset($titleTransferPerson) ? $titleTransferPerson->full_name : old('full_name') }}" />
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                            <label class="form-label fs-5" for="industry">Industry </label>
                            <input type="text" @if (isset($titleTransferPerson)) disabled @endif
                                class="form-control form-control-lg @error('industry') is-invalid @enderror"
                                id="industry" placeholder="Industry"
                                value="{{ isset($titleTransferPerson) ? $titleTransferPerson->occupation : old('industry') }}" />
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                            <label class="form-label fs-5" for="registration">Registration # <span
                                    class="text-danger">*</span></label>
                            <input type="text" @if (isset($titleTransferPerson)) disabled @endif
                                class="cp_cnic form-control form-control-lg @error('registration') is-invalid @enderror"
                                id="registration" placeholder="Registration Number"
                                value="{{ isset($titleTransferPerson) ? $titleTransferPerson->cnic : old('registration') }}" />

                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                            <label class="form-label fs-5" for="ntn">NTN </label>
                            <input type="number" @if (isset($titleTransferPerson)) disabled @endif
                                class="form-control form-control-lg @error('ntn') is-invalid @enderror"
                                id="ntn" placeholder="NTN Number"
                                value="{{ isset($titleTransferPerson) ? $titleTransferPerson->ntn : old('ntn') }}" />

                        </div>
                    </div>
                </div>

                {{-- common form  --}}
                <div class="row mb-1">
                    <div class="col-lg-3 col-md-3 col-sm-12 position-relative">
                        <label class="form-label fs-5" for="stackholder_email">Email <span
                                class="text-danger">*</span></label>
                        <input type="email" @if (isset($titleTransferPerson)) disabled @endif
                            class="form-control form-control-md @error('email') is-invalid @enderror"
                            id="stackholder_email" name="stackholder[email]" placeholder="Email"
                            autocomplete="false"
                            value="{{ isset($titleTransferPerson) ? $titleTransferPerson->email : old('email') }}" />
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-12 position-relative">
                        <label class="form-label fs-5" for="stackholder_optional_email">Optional Email</label>
                        <input type="email" @if (isset($titleTransferPerson)) disabled @endif
                            class="form-control form-control-md @error('email') is-invalid @enderror"
                            id="stackholder_optional_email" name="stackholder[optional_email]"
                            placeholder="Optional Email" autocomplete="false"
                            value="{{ isset($titleTransferPerson) ? $titleTransferPerson->optional_email : old('email') }}" />
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-12 position-relative">
                        <label class="form-label fs-5" for="stackholder_contact">Contact <span
                                class="text-danger">*</span></label>
                        <input @if (isset($titleTransferPerson)) disabled @endif type="text"
                            class="form-control form-control-lg ContactNoError" id="stackholder_contact" name="stackholder[contact]"
                            placeholder="Contact"
                            value="{{ isset($titleTransferPerson) ? $titleTransferPerson->contact : '' }}" />
                    </div>
                    <input type="hidden" name="stackholder[countryDetails]" id="countryDetails">

                    <div class="col-lg-3 col-md-3 col-sm-12">
                        <label class="form-label fs-5" for="stackholder_optional_contact">Optional Contact</label>
                        <input @if (isset($titleTransferPerson)) disabled @endif type="text"
                            class="form-control form-control-lg OPTContactNoError" id="stackholder_optional_contact"
                            name="stackholder[optional_contact]" placeholder="Optional Contact"
                            value="{{ isset($titleTransferPerson) ? $titleTransferPerson->optional_contact : '' }}" />

                    </div>
                    <input type="hidden" name="stackholder[OptionalCountryDetails]" id="OptionalCountryDetails">
                </div>
                <div class="row mb-1">

                    <div class="col-lg-3 col-md-3 col-sm-6 position-relative">
                        <label class="form-label" style="font-size: 15px" for="parent_id">Select
                            Country</label>
                        <select class="select2 country_id" id="country_id" name="stackholder[country_id]"
                            @if (isset($titleTransferPerson)) disabled @endif>
                            @if (isset($titleTransferPerson))
                                <option selected>{{ $titleTransferPerson->country->name ?? '' }}</option>
                            @else
                                <option value="0" selected>Select Country</option>

                                @foreach ($country as $data)
                                    <option value="{{ $data->id }}">{{ $data->name }}</option>
                                @endforeach
                            @endif
                        </select>
                        @error('country_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-6 position-relative">
                        <label class="form-label" style="font-size: 15px" for="city_id">Select
                            State</label>
                        <select class="select2 state_id" id="state_id" name="stackholder[state_id]"
                            @if (isset($titleTransferPerson)) disabled @endif>
                            @if (isset($titleTransferPerson))
                                <option selected>{{ $titleTransferPerson->state->name ?? '' }}</option>
                            @else
                                <option value="0" selected>Select State</option>
                            @endif
                        </select>
                        @error('state_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-6 position-relative">
                        <label class="form-label" style="font-size: 15px" for="city_id">Select
                            City</label>
                        <select class="select2 city_id" id="city_id" name="stackholder[city_id]"
                            @if (isset($titleTransferPerson)) disabled @endif>

                            @if (isset($titleTransferPerson))
                                <option selected>{{ $titleTransferPerson->city->name ?? '' }}</option>
                            @else
                                <option value="0" selected>Select City</option>
                            @endif
                        </select>
                        @error('city_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 position-relative">
                        <label class="form-label fs-5" for="occupation">Nationality </label>
                        <input type="text" @if (isset($titleTransferPerson)) disabled @endif
                            class="form-control form-control-lg @error('occupation') is-invalid @enderror"
                            id="nationality" name="nationality" placeholder="Nationality"
                            value="{{ isset($titleTransferPerson) ? $titleTransferPerson->nationality : old('nationality') }}" />
                        @error('nationality')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-lg-6 col-md-6 col-sm-12 position-relative">
                        <label class="form-label fs-5" for="stackholder_address">Address <span
                                class="text-danger">*</span></label>
                        <textarea @if (isset($titleTransferPerson)) disabled @endif class="form-control form-control-lg"
                            id="stackholder_address" name="stackholder[address]" placeholder="Address" rows="4"> {{ isset($titleTransferPerson) ? $titleTransferPerson->address : '' }}</textarea>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 position-relative">
                        <label class="form-label fs-5" for="mailing_address">Mailing Address <span
                                class="text-danger">*</span><span class="text-info">
                                @if (!isset($titleTransferPerson))
                                    ( Same as Permanent
                                    Address
                                    <input type="checkbox" id="cpyAddress" />
                                    )
                            </span>
                            @endif
                        </label>
                        <textarea @if (isset($titleTransferPerson)) disabled @endif class="form-control form-control-lg"
                            id="mailing_address" name="stackholder[mailing_address]" placeholder="Mailing Address" rows="4">{{ isset($titleTransferPerson) ? $titleTransferPerson->mailing_address : '' }}</textarea>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-lg- col-md- col-sm-12 position-relative">
                        <label class="form-label fs-5" for="stackholder_comments">Comments</label>
                        <textarea @if (isset($titleTransferPerson)) disabled @endif class="form-control form-control-lg"
                            id="stackholder_comments" name="stackholder[comments]" placeholder="Comments" rows="5">{{ isset($titleTransferPerson) ? $titleTransferPerson->comments : '' }}</textarea>
                    </div>
                </div>

                <div class="row mb-1" id="stakeholderNextOfKin">
                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                        <label class="form-label fs-5" for="stackholder_next_of_kin">Select Next Of Kin</label>
                        <select class="select2" multiple name="stackholder[next_of_kin][]"
                            id="stackholder_next_of_kin">

                        </select>
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
                <h3> File Owner Informaton </h3>
            </div>

            <div class="card-body">
                {{-- @dd($customer) --}}
                <div class="row mb-1">
                    <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                        <label class="form-label fs-5" for="stackholder_full_name">Full Name</label>
                        <input type="text" readonly value="{{ $customer->full_name }}"
                            class="form-control form-control-lg" id="" placeholder="Full Name" />
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                        <label class="form-label fs-5" for="stackholder_father_name">Father / Husband Name</label>
                        <input type="text" readonly value="{{ $customer->father_name }}"
                            class="form-control form-control-lg" id=""
                            placeholder="Father / Husband Name" />
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

                    <div class="col-lg-3 col-md-3 col-sm-6 position-relative">
                        <label class="form-label fs-5">NTN</label>
                        <input type="number" readonly value="{{ $customer->ntn }}"
                            class="form-control form-control-lg" placeholder="NTN" />
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-6 position-relative">
                        <label class="form-label fs-5">CNIC</label>
                        <input type="text" readonly value="{{ cnicFormat($customer->cnic) }}"
                            class="form-control form-control-lg" placeholder="CNIC" />
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-6 position-relative">
                        <label class="form-label fs-5">Contact</label>
                        <input type="text" readonly value="{{ $customer->contact }}"
                            class="form-control form-control-lg" id="" placeholder="Contact" />
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-6 position-relative">
                        <label class="form-label fs-5">Optional Contact</label>
                        <input type="text" readonly value="{{ $customer->optional_contact }}"
                            class="form-control form-control-lg" placeholder="Optional Contact" />
                    </div>
                </div>

                <div class="row mb-1">
                    <div class="col-lg-6 col-md-6 position-relative">
                        <label class="form-label fs-5" for="email">Email <span
                                class="text-danger">*</span></label>
                        <input type="email"
                            class="form-control form-control-lg @error('email') is-invalid @enderror" name="email"
                            placeholder="Email" autocomplete="false" readonly
                            value="{{ isset($customer) ? $customer->email : old('email') }}" />
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-lg-6 col-md-6 position-relative">
                        <label class="form-label fs-5" for="optional_email">Optional Email</label>
                        <input type="email"
                            class="form-control form-control-lg @error('email') is-invalid @enderror"
                            name="optional_email" placeholder="Optional Email" autocomplete="false" readonly
                            value="{{ isset($customer) ? $customer->optional_email : old('email') }}" />
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-lg-6 col-md-6 col-sm-12 position-relative">
                        <label class="form-label fs-5" for="stackholder_address">Address</label>
                        <textarea class="form-control  form-control-lg" readonly id="" name="" placeholder="Address"
                            rows="4">{{ $customer->address }}</textarea>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-12 position-relative">
                        <label class="form-label fs-5" for="stackholder_address">Mailling Address</label>
                        <textarea class="form-control  form-control-lg" readonly id="" name="" placeholder="Address"
                            rows="4">{{ $customer->mailing_address }}</textarea>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-lg col-md col-sm-12 position-relative">
                        <label class="form-label fs-5" for="stackholder_comments">Comments</label>
                        <textarea class="form-control form-control-lg" readonly id="" name="" placeholder="Comments"
                            rows="4">{{ $customer->comments }}</textarea>
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

                <div class="row mb-1">

                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                        <label class="form-label fs-5" for="stackholder_occupation">Gross Area</label>
                        <input type="text" readonly value="{{ number_format($unit->gross_area) }}"
                            class="form-control form-control-lg" id="stackholder_occupation" placeholder="Unit No" />
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                        <label class="form-label fs-5" for="stackholder_full_name">Price Per Sqft</label>
                        <input type="text" readonly value="{{ number_format($salesPlan->unit_price) }}"
                            class="form-control form-control-lg" id="stackholder_full_name"
                            placeholder="Unit Name" />
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                        <label class="form-label fs-5" for="stackholder_father_name">Total Price</label>
                        <input type="text" readonly value="{{ number_format($salesPlan->total_price) }}"
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
                                            @if (isset($unit->salesPlan[0]))
                                                @php
                                                    $imstallments = collect($unit->salesPlan[0]['installments'])
                                                        ->sortBy('installment_order')
                                                        ->values()
                                                        ->all();
                                                @endphp

                                                @foreach ($imstallments as $intsallment)
                                                    <tr class="text-center text-nowrap">
                                                        <td>{{ $loop->index }}</td>
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
                                            @else
                                                @php
                                                    $imstallments = collect($unit->CancelsalesPlan[0]['installments'])
                                                        ->sortBy('installment_order')
                                                        ->values()
                                                        ->all();
                                                @endphp
                                                @foreach ($imstallments as $intsallment)
                                                    <tr class="text-center text-nowrap">
                                                        <td>{{ $loop->index + 1 }}</td>
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
                                            @endif

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
                                {{ isset($transfer_file) ? 'disabled' : '' }} placeholder="Comments" rows="5">{{ isset($transfer_file) ? $transfer_file->comments : '' }}</textarea>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>

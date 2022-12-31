<style>
    .agreement_col {
        border: 1px solid;
        max-height: 40vh;
        height: 100vh;
    }

    .custom_detail_tbl,
    tbody,
    th,
    tr,
    td {
        border: 1px dotted;
    }

    .main_agreement_scroll {
        overflow-y: scroll;
        height: 80vh;
    }
</style>
<div class="bs-stepper wizard-modern modern-wizard-example">

    <div class="bs-stepper-header">

        <div class="step" data-target="#applicaiton-form" role="tab" id="applicaiton-form-trigger">
            <button type="button" class="step-trigger">
                <span class="bs-stepper-box waves-effect waves-float waves-light" class="font-medium-3">1</span>
                <span class="bs-stepper-label">
                    <span class="bs-stepper-title">Application Form</span>
                    <span class="bs-stepper-subtitle">Validate Application Form</span>
                </span>
            </button>
        </div>

        <div class="line">
            <i data-feather="chevron-right" class="font-medium-2"></i>
        </div>

        <div class="step" data-target="#sales-agreement" role="tab" id="sales-agreement-trigger">
            <button type="button" class="step-trigger">
                <span class="bs-stepper-box waves-effect waves-float waves-light" class="font-medium-3">2</span>
                <span class="bs-stepper-label">
                    <span class="bs-stepper-title">Sales Agreement</span>
                    <span class="bs-stepper-subtitle">Validate Sales Agreement</span>
                </span>
            </button>
        </div>

        <div class="line">
            <i data-feather="chevron-right" class="font-medium-2"></i>
        </div>

        <div class="step" data-target="#sales-plan" role="tab" id="sales-plan-trigger">
            <button type="button" class="step-trigger">
                <span class="bs-stepper-box waves-effect waves-float waves-light" class="font-medium-3">3</span>
                <span class="bs-stepper-label">
                    <span class="bs-stepper-title">Sales Plan</span>
                    <span class="bs-stepper-subtitle">Validate Sales Plan</span>
                </span>
            </button>
        </div>

        <div class="line">
            <i data-feather="chevron-right" class="font-medium-2"></i>
        </div>

        <div class="step" data-target="#receipts" role="tab" id="receipts-trigger">
            <button type="button" class="step-trigger">
                <span class="bs-stepper-box waves-effect waves-float waves-light" class="font-medium-3">4</span>
                <span class="bs-stepper-label">
                    <span class="bs-stepper-title">Receipts</span>
                    <span class="bs-stepper-subtitle">Validate Receipts</span>
                </span>
            </button>
        </div>

        <div class="line">
            <i data-feather="chevron-right" class="font-medium-2"></i>
        </div>

        <div class="step" data-target="#booking-form" role="tab" id="booking-form-trigger">
            <button type="button" class="step-trigger">
                <span class="bs-stepper-box waves-effect waves-float waves-light" class="font-medium-3">5</span>
                <span class="bs-stepper-label">
                    <span class="bs-stepper-title">Booking Form</span>
                    <span class="bs-stepper-subtitle">Validate Booking Form</span>
                </span>
            </button>
        </div>

        <div class="line">
            {{-- <i data-feather="chevron-right" class="font-medium-2"></i> --}}
        </div>

        {{-- <div class="step" data-target="#rebate-form" role="tab" id="rebate-form-trigger">
            <button type="button" class="step-trigger">
                <span class="bs-stepper-box waves-effect waves-float waves-light" class="font-medium-3">6</span>
                <span class="bs-stepper-label">
                    <span class="bs-stepper-title">Rebate Form</span>
                    <span class="bs-stepper-subtitle">Validate Rebate Form</span>
                </span>
            </button>
        </div> --}}
    </div>

    <div class="bs-stepper-content p-0">

        <div class="card content shadow-none m-0" id="applicaiton-form" role="tabpanel"
            aria-labelledby="applicaiton-form-trigger">
            <div class="card-body">
                <div class="row g-1 mb-2">
                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                        <label class="form-label fs-5" for="registration_no">Registration No <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-lg" id="registration_no"
                            @isset($customer_file)
                             value="{{ $customer_file->registration_no }}" readonly
                            @endisset
                            name="application_form[registration_no]" placeholder="Registration No" />
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                        <label class="form-label fs-5" for="application_no">Application No <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-lg" id="application_no"
                            @isset($customer_file)
                             value="{{ $customer_file->application_no }}" readonly
                            @endisset
                            name="application_form[application_no]" placeholder="Application No" />
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                        <label class="form-label fs-5" for="application_no">Note Serial Number <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-lg" id="note_serial_number"
                            @isset($customer_file)
                             value="{{ $customer_file->note_serial_number }}" readonly
                            @endisset
                            name="application_form[note_serial_number]" placeholder="Note Serial Number" />
                    </div>
                </div>

                <div class="row mb-1">
                    <div class="col-lg-10 col-md-10 col-sm-12">
                        {{-- Units Data --}}
                        <div class="row mb-1">
                            <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                                <div class="card m-0"
                                    style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                                    <div class="card-header">
                                        <h3>Units</h3>
                                    </div>

                                    <div class="card-body">
                                        <div class="row g-1 mb-1">
                                            <input type="hidden" name="application_form[unit_id]"
                                                value="{{ $unit->id }}">
                                            <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                                <label class="form-label fs-5" for="unit_no">Unit No</label>
                                                <input type="text" class="form-control form-control-lg"
                                                    id="unit_no" placeholder="Unit No"
                                                    value="{{ $unit->floor_unit_number }}" disabled />
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                                <label class="form-label fs-5" for="unit_type">Unit Type</label>
                                                <input type="text" class="form-control form-control-lg"
                                                    id="unit_type" value="{{ $unit->type->name }}"
                                                    placeholder="Unit Type" disabled />
                                            </div>
                                        </div>

                                        <div class="row g-1">
                                            <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                                <label class="form-label fs-5" for="unit_size">Size</label>
                                                <input type="text" class="form-control form-control-lg"
                                                    id="unit_size" placeholder="Size"
                                                    value="{{ $unit->gross_area }} sqft" disabled />
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                                <label class="form-label fs-5" for="unit_floor">Floor</label>
                                                <input type="text" class="form-control form-control-lg"
                                                    id="unit_floor" placeholder="Floor"
                                                    value="{{ $unit->floor->short_label }}" disabled />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-2 col-sm-12">
                        <label class="form-label fs-5" for="application_photo">Photo <span
                                class="text-danger">*</span></label>
                        <input @if (isset($customer_file)) disabled @endif id="application_photo" type="file"
                            class="filepond" name="application_form[photo]" accept="image/png, image/jpeg" />
                    </div>
                </div>

                {{-- Stakeholder Data --}}
                <div class="row mb-1">
                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                        <div class="card m-0"
                            style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                            <div class="card-header">
                                <h3>Customer</h3>
                            </div>

                            <div class="card-body">

                                <div class="row g-1 mb-1">
                                    <input type="hidden" name="application_form[stakeholder_id]"
                                        value="{{ $customer->id }}">

                                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                        <label class="form-label fs-5" for="customer_name">Name</label>
                                        <input type="text" class="form-control form-control-lg" id="customer_name"
                                            placeholder="Name" value="{{ $customer->full_name }}" disabled />
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                        <label class="form-label fs-5" for="customer_father_name">Father/Husband
                                            Name</label>
                                        <input type="text" class="form-control form-control-lg"
                                            id="customer_father_name" value="{{ $customer->father_name }}"
                                            placeholder="Father/Husband Name" disabled />
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                        <label class="form-label fs-5" for="customer_cnic">CNIC/Passport</label>
                                        <input type="text" class="form-control form-control-lg" id="customer_cnic"
                                            placeholder="CNIC/Passport"
                                            value="{{ cnicFormat($customer->cnic) ?? '-' }}" disabled />
                                    </div>
                                </div>

                                <div class="row g-1 mb-1">
                                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                        <label class="form-label fs-5" for="customer_ntn">NTN Number</label>
                                        <input type="text" class="form-control form-control-lg" id="customer_ntn"
                                            placeholder="NTN Number" value="{{ $customer->ntn ?? '-' }}" disabled />
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                        <label class="form-label fs-5" for="customer_phone">Cell</label>
                                        <input type="text" class="form-control form-control-lg"
                                            id="customer_phone" placeholder="Cell"
                                            value="{{ $customer->contact ?? '-' }}" disabled />
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                        <label class="form-label fs-5" for="customer_occupation">Occupation</label>
                                        <input type="text" class="form-control form-control-lg"
                                            id="customer_occupation" placeholder="Occupation"
                                            value="{{ $customer->occupation ?? '-' }}" disabled />
                                    </div>
                                </div>

                                <div class="row g-1 mb-1">
                                    <div class="col-lg-6 col-md-6 col-sm-12 position-relative">
                                        <label class="form-label fs-5" for="customer_address">Address</label>
                                        <input type="text" class="form-control form-control-lg"
                                            id="customer_address" placeholder="Address"
                                            value="{{ $customer->address ?? '-' }}" disabled />
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-12 position-relative">
                                        <label class="form-label fs-5" for="customer_comments">Comments</label>
                                        <input type="text" class="form-control form-control-lg"
                                            id="customer_comments" placeholder="Comments"
                                            value="{{ $customer->comments ?? '-' }}" disabled />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Next Of KIN Data --}}
                @forelse ($nextOfKin as $kin)
                    <div class="row mb-1">
                        <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                            <div class="card m-0"
                                style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                                <div class="card-header">
                                    <h3>Next Of KIN</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row g-1 mb-1">
                                        <input type="hidden" name="application_form[stakeholder_id]"
                                            value="{{ $kin->id }}">

                                        <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                            <label class="form-label fs-5" for="customer_name">Name</label>
                                            <input type="text" class="form-control form-control-lg"
                                                id="customer_name" placeholder="Name" value="{{ $kin->full_name }}"
                                                disabled />
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                            <label class="form-label fs-5" for="customer_father_name">Father/Husband
                                                Name</label>
                                            <input type="text" class="form-control form-control-lg"
                                                id="customer_father_name" value="{{ $kin->father_name }}"
                                                placeholder="Father/Husband Name" disabled />
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                            <label class="form-label fs-5"
                                                for="customer_relationship">Relationship</label>
                                            <input type="text" class="form-control form-control-lg"
                                                id="customer_relationship" value="{{ $kin->relation }}"
                                                placeholder="Relationship" disabled />
                                        </div>
                                    </div>

                                    <div class="row g-1 mb-1">
                                        <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                            <label class="form-label fs-5" for="customer_cnic">CNIC/Passport</label>
                                            <input type="text" class="form-control form-control-lg"
                                                id="customer_cnic" placeholder="CNIC/Passport"
                                                value="{{ cnicFormat($kin->cnic) }}" disabled />
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                            <label class="form-label fs-5" for="customer_phone">Cell</label>
                                            <input type="text" class="form-control form-control-lg"
                                                id="customer_phone" placeholder="Cell" value="{{ $kin->contact }}"
                                                disabled />
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                            <label class="form-label fs-5"
                                                for="customer_occupation">Occupation</label>
                                            <input type="text" class="form-control form-control-lg"
                                                id="customer_occupation" placeholder="Occupation"
                                                value="{{ $kin->occupation }}" disabled />
                                        </div>
                                    </div>

                                    <div class="row g-1 mb-1">
                                        <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                                            <label class="form-label fs-5" for="customer_address">Address</label>
                                            <input type="text" class="form-control form-control-lg"
                                                id="customer_address" placeholder="Address"
                                                value="{{ $kin->address }}" disabled />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                @endforelse
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-end">

                    <button class="btn btn-relief-outline-primary waves-effect waves-float waves-light btn-next"
                        type="button">
                        <span class="align-middle d-sm-inline-block d-none">Next</span>
                        <i data-feather="arrow-right" class="align-middle ms-sm-25 ms-0"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="card content shadow-none m-0" id="sales-agreement" role="tabpanel"
            aria-labelledby="sales-agreement-trigger">
            <div class="card-body main_agreement_scroll">
                <div>
                    <h1 class="text-bold text-underline text-center p-3"><u><strong>Agreement</strong></u></h1>
                    <div class="row">
                        <div class="col px-3">
                            <p>This Agreement is made at Plot no. MC-E1, Quaid Road West,
                                Blue Zone situated in Mumtaz City, Rawalpindi on this 30-12-2022
                            </p><br><br>
                            <span class="text-bold h4"><strong>BETWEEN:</strong></span>
                            <p> <strong> <u> J7 GLOBAL, a partnership firm having its office at Plot No. MC-E1,
                                        Quaid Road West, Blue
                                        Zone, Mumtaz City, Rawalpindi,</u></strong> (hereinafter referred to as the
                                “Seller/Vendor” which
                                expression shall, where the context so admits, means and include his nominees,
                                successors, successors-in-title, administrators, or assigners) The party of the
                                First
                                Part </p><br>
                            <div class="text-ce">AND</div><br>
                            <p>Mr./Ms. son/daughter/wife of having CNIC No. or Passport No. ___(as applicable),
                                resident
                                of _________(hereinafter referred to as the
                                <strong>“Buyer</strong>/<strong>Vendee”</strong> which expression shall,
                                where the context so admits, means and include his nominees, successors,
                                successors-in-title) and further detailed as mentioned in the
                                <strong>Schedule-A</strong>. The party
                                of
                                the Second Part
                            </p>
                            <span class="text-bold h4"><strong> WHEREAS:</strong></span><br>
                            <ol>
                                <li type="i">The Seller/Vendor being the absolute owner is
                                    developing/constructing a real estate
                                    project named and styled as ‘’<strong>J7 GLOBAL</strong>” located at, Plot No.
                                    MC-E1, Quaid Road,
                                    West,
                                    Blue Zone, Mumtaz City, Rawalpindi (“<strong>Project</strong>”) (hereinafter
                                    referred to as the
                                    “<strong>PROJECT</strong>”),</li><br>
                                <li type="i">The Buyer/Vendee has seen the plan for the PROJECT and desires
                                    to acquire and
                                    purchase from the Seller/Vendor a Unit in the Project, the Unit No. _________
                                    (insert
                                    one as applicable) (hereinafter referred to as the <strong>“Unit”</strong>);
                                </li><br>
                                <li type="i">The Seller/Vendor has agreed to sell and the Buyer/Vendee has
                                    agreed to buy the
                                    Unit, at the under-construction <strong>PROJECT</strong> against the agreed
                                    consideration in
                                    installments
                                    as detailed in the <strong>Schedule-B</strong>.</li>
                            </ol>
                            <br>

                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div>
                            <p><strong>NOW, THEREFORE,</strong> the Parties, intending to be legally bound, agree to the
                                following terms and conditions of this Agreement along with two Schedules
                                appended hereto, which shall form an integral part of this Agreement:
                            </p>

                            <div class="row">
                                <div class="col-md-6 col-6 ">
                                    <div class="agreement_col">For J7 GLOBAL <br>Name:</div>
                                </div>
                                <div class="col-md-6 col-6">
                                    <div class="agreement_col">Name:</div>
                                </div>
                            </div>
                        </div><br><br>
                        <span class="text-bold h4"><strong>WITNESSES:</strong></span>
                        <div class="row mt-1">
                            <div class="col-md-6">Name:</div>
                            <div class="col-md-6">Name:</div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h4><strong>DEFINITIONS AND INTERPRETATION: </strong> </h4>
                        <table class="table">
                            <tbody>
                                <tr class="custom_detail_tbl">
                                    <th class="">Agreement</th>
                                    <td class="w-100">
                                        means this Agreement of Sale and Purchase, as may be amended or supplemented by
                                        the
                                        Seller/Vendor from time to time at its sole discretion.
                                    </td>
                                </tr>
                                <tr class="custom_detail_tbl">
                                    <th class="">Buyer/Vendee</th>
                                    <td class="w-100">
                                        means the natural or legal person or persons identified as the Buyer/Vendee in
                                        this
                                        Agreement and his/her or their respective heirs, executors, successors,
                                        nominees,
                                        successors-in-title, permitted transferees and permitted assigns. Where there is
                                        more than one Buyer, references to one Buyer/Vendee shall be to such persons
                                        jointly
                                        and severally.
                                    </td>
                                </tr>
                                <tr class="custom_detail_tbl">
                                    <th class="">Common Areas</th>
                                    <td class="w-100">
                                        means those parts of the PROJECT not physically forming a part of any Unit and
                                        are
                                        intended for the common use of all occupants or owners of Units, including inter
                                        alia all open areas, elevators, administrative office areas, security system,
                                        lobbies, Parking Area and hallways.
                                    </td>
                                </tr>
                                <tr class="custom_detail_tbl">
                                    <th class="">Common Facilities</th>
                                    <td class="w-100">
                                        means all roads, pavements, water features, installations, elevators, power
                                        backup
                                        equipment, security system, tube-well motors, improvements and common assets of
                                        the
                                        Common Areas that are intended for facilitating all occupants and owners of
                                        Units
                                        and do not form part of any given Unit.
                                    </td>
                                </tr>
                                <tr class="custom_detail_tbl">
                                    <th class="">Government Authority</th>
                                    <td class="w-100">
                                        means any federal, provincial, capital or local government or authority,
                                        department,
                                        local, regulatory, statutory or housing authority, including the Office of the
                                        Cantonment Board Rawalpindi, Office of the Military Estates Officer Rawalpindi,
                                        and
                                        any other authority which has jurisdiction over any aspect or part of the
                                        Project or
                                        the Agreement.
                                    </td>
                                </tr>
                                <tr class="custom_detail_tbl">
                                    <th class="">Anticipated Date of Completion</th>
                                    <td class="w-100">
                                        means the date or expected date as mentioned/incorporated in this Agreement, on
                                        which the Unit will be ready for handover of possession to the Buyer/Vendee,
                                        subject
                                        to the Buyer/Vendee complying with his/her obligations as per this Agreement.
                                    </td>
                                </tr>
                                <tr class="custom_detail_tbl">
                                    <th class="">Completion Notice</th>
                                    <td class="w-100">
                                        means a written notice sent to the Buyer/Vendee by the Seller/Vendor
                                        communicating
                                        the Date of Completion of the Unit.
                                    </td>
                                </tr>
                                <tr class="custom_detail_tbl">
                                    <th class="">Down Payment</th>
                                    <td class="w-100">
                                        means the amount paid by the Buyer/Vendee under this Agreement, equal to __%
                                        (____
                                        percent) of the Purchase Price of the Purchased Unit, as shown in the Payment
                                        Plan-Schedule-B.
                                    </td>
                                </tr>
                                <tr class="custom_detail_tbl">
                                    <th class="">Due Date</th>
                                    <td class="w-100">
                                        means the date on or before which any payment or installment in the Payment Plan
                                        is
                                        required to be paid by the Buyer/Vendee to the Seller/Vendor in the designated
                                        bank
                                        account.
                                    </td>
                                </tr>
                                <tr class="custom_detail_tbl">
                                    <th class="">Nominee</th>
                                    <td class="w-100">
                                        means such person, as may be nominated by the Buyer/Vendee as the person who may
                                        facilitate in the transfer of rights and obligations of the Buyer/Vendee under
                                        the
                                        Agreement to the legal heirs in the event of demise of the Buyer/Vendee.
                                    </td>
                                </tr>
                                <tr class="custom_detail_tbl">
                                    <th class="">Parking Area</th>
                                    <td class="w-100">
                                        means the demarcated parking area for the exclusive use of visitors and
                                        occupants of
                                        the PROJECT, the exact position whereof is to be allocated on or before the
                                        Completion Date.
                                    </td>
                                </tr>
                                <tr class="custom_detail_tbl">
                                    <th class="">Parties</th>
                                    <td class="w-100">
                                        The Seller/Vendor and the Buyer/Vendee, where the context so permits, and each
                                        individually as a “Party”.
                                    </td>
                                </tr>
                                <tr class="custom_detail_tbl">
                                    <th class="">Payment Plan</th>
                                    <td class="w-100">
                                        means the Payment Plan in Schedule-B of the Agreement, setting out the Due Date,
                                        quantum and schedule of Installment Payments of the Purchase Price to be paid by
                                        the
                                        Buyer/Vendee.
                                    </td>
                                </tr>
                                <tr class="custom_detail_tbl">
                                    <th class="">Property Manager</th>
                                    <td class="w-100">
                                        means the entity formed by the Seller/Vendor, which may include the Seller, any
                                        of
                                        its affiliates or associated companies or any other company (and their
                                        respective
                                        successors in interest and assigns) charged with the maintenance, operation or
                                        management of the PROJECT or any part thereof, including inter alia Parking
                                        Area,
                                        Common Areas and/or Common Facilities.
                                    </td>
                                </tr>
                                <tr class="custom_detail_tbl">
                                    <th class="">Project</th>
                                    <td class="w-100">
                                        means the multistorey Building being constructed and developed by the
                                        Seller/Vendor,
                                        named and advertised as <strong>J7 Global</strong>, approved and sanctioned by
                                        the
                                        <strong>J7 Global</strong> and/or
                                        any other relevant Government office/authority.
                                    </td>
                                </tr>
                                <tr class="custom_detail_tbl">
                                    <th class="">Unit</th>
                                    <td class="w-100">
                                        means the specific Unit _____, as shown in Schedule-A being the Purchased Unit,
                                        which the Buyer/Vendee has contracted to purchase pursuant to this Agreement.
                                    </td>
                                </tr>
                                <tr class="custom_detail_tbl">
                                    <th class="">Service Charges</th>
                                    <td class="w-100">
                                        means the amount payable by the Buyer/Vendee to the Property Manager on monthly
                                        basis in advance as the proportionate contribution towards the management,
                                        administration, maintenance and control of the Common Areas, Common Facilities
                                        and
                                        Parking Area, which amount shall be determined and prescribed from time to time
                                        by
                                        the Property Manager provided that Service Charges shall always be reasonable,
                                        based
                                        on market standards and as per fair market rates.
                                    </td>
                                </tr>
                                <tr class="custom_detail_tbl">
                                    <th class="">Taxes</th>
                                    <td class="w-100">
                                        means any federal, provincial or local taxes or levies payable on any component
                                        used
                                        for the construction of the Purchased Unit or, where the context permits,
                                        applicable
                                        on the sale or lease thereof, as may be prescribed or levied from time to time
                                        by
                                        any Government Authority.
                                    </td>
                            </tbody>
                        </table>
                    </div>
                </div>
                    <div class="card">
                        <div class="card-body">
                            <ol>
                                <li><strong>THE PROJECT</strong>
                                    <ul>
                                        <li type="a">The Buyer/Vendee acknowledges and understands that the
                                            Seller/Vendor
                                            is the Owner of
                                            the PROJECT by the name of <strong>J7 Global</strong>. The Seller/Vendor
                                            will
                                            develop the PROJECT
                                            into a ____________________ complex with certain shared facilities, parking
                                            and
                                            amenities.</li><br>
                                        <li type="a">The Buyer/Vendee acknowledges and understands that the
                                            Seller/Vendor
                                            shall remain
                                            owner of the land in the Project and that for the proper and convenient
                                            management,
                                            administration, maintenance and control of the Project, mutually beneficial
                                            restrictions are imposed on all the properties in the Project by the
                                            Seller/Vendor
                                            which establishes a mutually beneficial scheme for the management,
                                            administration,
                                            maintenance and control of the Project.</li><br>
                                        <li type="a">The Buyer/Vendee acknowledges and understands that for the
                                            proper
                                            and convenient
                                            management of the PROJECT, the Seller/Vendor may enter into any legal
                                            arrangement,
                                            with any person including but not limited to any firm, Company, any other
                                            legal
                                            entity.</li>
                                    </ul>
                                </li><br>
                                <li><strong>THE SALE</strong>
                                    <ul>
                                        <li type="a">The Seller/Vendor hereby sells to the Buyer/Vendee who
                                            hereby
                                            purchases the Unit in accordance with the terms and conditions contained in
                                            this
                                            Agreement, along with schedules appended hereto, shall form an integral
                                            part.
                                        </li>
                                        <br>
                                        <li type="a">This Agreement to sell is subject to execution of the Full
                                            and
                                            Final
                                            Agreement, Buy-Back Agreement and Lease-Back Agreement to be executed
                                            between
                                            the
                                            parties in due course of time as and when those Agreements will be finalized
                                            by
                                            the
                                            management of the Project J7 Global.</li>
                                    </ul>
                                </li><br>
                                <li><strong>Execution of Full and Final Agreement</strong>
                                    <ul>
                                        <li type="a">The Seller/Vendor is desirous and in the process of
                                            negotiation
                                            with
                                            multiple International Hotel Chains, and only upon the competition of those
                                            negotiations the developer shall execute the Full and Final Agreement with
                                            the
                                            Buyer.</li><br>
                                        <li type="a">The Buyer/Vendee hereby agrees and acknowledges that it
                                            shall be
                                            bound to execute the Full and Final Agreement with the Developer. The Full
                                            and
                                            Final
                                            Agreement shall be executed at the instance of the Developer.</li><br>
                                        <li type="a">Till the execution of the Full and Final Agreement, this
                                            Agreement
                                            to sell shall govern the relationship between the parties, and when the Full
                                            and
                                            Final Agreement is executed that shall supersede this agreement to sell.
                                        </li>
                                    </ul>
                                </li><br>
                                <li><strong>PURCHASE PRICE AND PAYMENT</strong>
                                    <ul>
                                        <li type="a">Only one application form can be used for booking of one
                                            Unit
                                            only.
                                            Additionally, Seller/Vendor is the Sole Entity authorized to issue such
                                            bookings
                                            and
                                            recording such sales.</li><br>
                                        <li type="a">The Buyer/Vendee undertakes to ensure that the Property
                                            Reservation
                                            Fee and on-going installments shall be paid in Pakistani Rupees (PKR) (the
                                            local
                                            currency) of the Islamic Republic of Pakistan and any short fall in payment
                                            due
                                            to
                                            the fluctuation in case of the currency exchange rate shall be on the
                                            Buyer/Vendee’s
                                            account and shall be immediately rectified by the Buyer/Vendee.</li><br>
                                        <li type="a">All Down payments should be made according to type and size
                                            of
                                            the Unit in the booking/sales office located on the site, as per schedule of
                                            payments through bank draft/pay order/cheque in the favor of
                                            <strong>“J7 sGlobal”</strong> with
                                            <strong>consent of</strong> the Seller/Vendor.
                                        </li><br>
                                        <li type="a">All subsequent installments for purchase of Unit shall be
                                            made
                                            in favors of the (the Seller/Vendor) account i.e. _______________. </li><br>
                                        <li type="a">Any and all payments from the Buyer/Vendee will only be
                                            considered
                                            as completed once the Seller/Vendor has issued a receipt voucher against the
                                            payment
                                            and furnished a receipt copy to the Buyer/Vendee which is not to be
                                            unreasonably
                                            withheld by the Seller/Vendor.</li><br>
                                        <li type="a">The Buyer/Vendee must pay each installment of the purchase
                                            price
                                            on
                                            or before the dates stipulated in the Payment Schedule as detailed in
                                            Schedule-B, to
                                            the Seller/Vendee’s account via cash, banker’s Cheques, wire transfer, money
                                            gram or
                                            any other mutually agreed upon mode of payment.</li>
                                    </ul>
                                </li><br>
                                <li><strong>Possession and Risk</strong>
                                    <ul>
                                        <li type="a">The completion date represents the date upon which it is
                                            expected at
                                            the time of entering into this Agreement that construction of the J7 Global
                                            will
                                            be
                                            completed, and the Unit will be ready for occupation. However, Seller/Vendor
                                            reserves the right to extend the completion date by a maximum period of six
                                            (6)
                                            months under intimation to the Buyer/Vendee prior to 31st December, 2025.
                                        </li>
                                        <br>
                                        <li type="a">Subject to the terms of this agreement, in the event
                                            Seller/Vendor
                                            is unable to complete the J7 Global and handover possession of the unit to
                                            the
                                            Buyer/Vendee by the completion date (or as extended per Article 5a here in
                                            above),
                                            Seller/Vendor shall pay to the Buyer/Vendee, who timely paid all
                                            his/her/their
                                            instalments (which is the buyer’s exclusive remedy in case of delay in
                                            completion)
                                            0.5% of the received amount to date, per month, until the completion of the
                                            J7
                                            Global.</li><br>
                                        <li type="a">The possession of Units Will not be allotted and/or handed
                                            over
                                            to
                                            the client, and the possession of the Project and the Unit shall remain with
                                            the
                                            Seller/Vendor. </li><br>
                                        <li type="a">On or before the Completion Date, Seller/Vendor shall issue
                                            a
                                            notice
                                            of possession to the Buyer/Vendee, requiring the Buyer/vendee:
                                            <ul>
                                                <li type="i">To pay any outstanding portion of the Sale Price and
                                                    other
                                                    charges, if any
                                                </li>
                                                <li type="i">To take possession of the unit on or before the date
                                                    specified in the notice
                                                    of possession.</li>
                                            </ul>
                                            In the event the purchaser does not take possession of the unit on or before
                                            such
                                            date, the purchaser shall be liable to pay penalties as specified in the
                                            notice
                                            of
                                            possession to without affecting any other right or remedy of under this
                                            agreement.
                                        </li><br>
                                        <li type="a">Seller/Vendor shall be entitled to decline the transfer of
                                            title
                                            and
                                            retain or resume possession of the unit from the purchaser in the event the
                                            purchaser fails to pay the sale price as specified here in above or fails to
                                            comply
                                            with any other provisions of this agreement.</li><br>
                                        <li type="a">That the project is to be constructed on the Plot no. MC-E1,
                                            Quaid
                                            Road West, Blue Zone situated in Mumtaz City, Rawalpindi which is allotted
                                            to
                                            the
                                            sponsors with the nature of “Commercialized” use. However, due to any reason
                                            whatsoever, in future, if the intended usage is not allowed or changed by
                                            the
                                            regulatory authority, then the Seller/Vendor shall have the right to cancel
                                            this
                                            sale agreement and return the amount to the Buyer/Vendee without any penalty
                                            or
                                            additional charges and no objection of the Buyer/Vendee in this regard shall
                                            be
                                            acceptable.</li><br>
                                        <li type="a">If Buyer/Vendee fails to clear dues before 31 December 2025,
                                            then
                                            Seller/Vendor will not be bound to transfer rental income to Buyer/Vendee
                                            and
                                            this
                                            income will be income of Seller/Vendor.</li><br>
                                    </ul>
                                </li><br>
                                <li><strong>Payment of Taxes</strong>
                                    <ul>
                                        <li type="a">The Buyer/Vendee will be responsible for payment of all
                                            taxes,
                                            rates, charges, impositions, duties (howsoever designated), costs of
                                            assessment
                                            and
                                            including but not limited to penalties in respect of the Unit.</li>
                                    </ul>
                                </li><br>
                                <li><strong>Sale and Assignment</strong>
                                    <ul>
                                        <li type="a">If the Buyer/Vendee desires to sell its Unit then the
                                            Seller/Vendor
                                            shall have the First Right of Refusal before the Buyer/Vendee can sell the
                                            Unit
                                            to
                                            any other person. The Buyer/Vendee shall first give notice in writing to the
                                            Seller/Vendor in writing of its desire to sell the Unit. Whereafter, the
                                            Seller/Vendor shall have a period of 30 days from the receipt of the Notice
                                            to
                                            exercise its to purchase the Unit. In case the Seller/Vendor decided to
                                            purchase
                                            the
                                            Unit, it shall then give notice in writing to the Buyer/Vendee, the notice
                                            should be
                                            sent to the Buyer/Vendee within 30 days of the receipt of the Buyer/Vendee’s
                                            written
                                            Notice to sell. Whereafter, both the parties shall mutually decide the price
                                            of
                                            the
                                            Unit.</li><br>
                                        <li type="a">Buyer/Vendee can sell his/her inventory through J7 Global
                                            sales
                                            team
                                            or by any other source at any stage at each resale transfer fee of RS. 100
                                            /Sq.
                                            Ft
                                            for purchased unit will be charged by J7 Global management. J7 Global may
                                            impose
                                            additional terms and conditions at its sole discretion.</li><br>
                                        <li type="a">Notwithstanding anything to the contrary in this agreement,
                                            the
                                            Buyer/Vendee shall not create or permit to exist any mortgage, lien
                                            (excepting
                                            the
                                            lien in favor of J7 Global under this agreement) or charge or encumbrance
                                            (of
                                            whatever nature and howsoever described) over or in respect of the unit or
                                            the
                                            purchaser’s interest therein without the prior written approval of J7
                                            Global.
                                        </li>
                                    </ul>
                                </li><br>
                                <li><strong>Modifications/Variation</strong>
                                    <ul>
                                        <li type="a">The Buyer/Vendee understands that the figures, descriptions,
                                            pictures, layouts, and other details pertaining to the Project and the
                                            Property
                                            and
                                            references to conditions necessary for transfer, use and occupation of the
                                            Property
                                            are indicative and are given in good faith by the Seller/Vendor are believed
                                            to
                                            be
                                            correct as on the date of this Agreement. Such information may change from
                                            time
                                            to
                                            time in accordance with the final design of the project and planning
                                            permissions
                                            and
                                            such information may be subject to change by the Seller/Vendor without
                                            notice to
                                            the
                                            Buyer/Vendee. The Buyer/Vendee acknowledges and agrees that the
                                            Seller/Vendor
                                            may
                                            from time to time in its sole discretion or as required by any competent
                                            authority
                                            change, vary, modify the plans, contours, materials, finishes, equipment,
                                            fixtures,
                                            and specifications pertaining to the Project, and the Property mentioned
                                            herein
                                            without notice to the Buyer.</li><br>
                                        <li type="a">The Buyer/Vendee considering the investment in the Property
                                            has
                                            studied the documents, plans, documentation and requirements for transfer
                                            and
                                            registration, the Property’s plans, on-going payment schedule.</li>
                                    </ul>
                                </li><br>
                                <li><strong>Refund Policy</strong>
                                    <ul>
                                        <li type="a">In case the Buyer/Vendee wishes to terminate this agreement
                                            prior to
                                            making full and final payment or transferring title in the Unit, and a
                                            refund is
                                            claimed, the J7 Global management shall have the right to deduct 25% of the
                                            total
                                            amount paid of the sold unit, balance will be paid to purchaser without any
                                            profit
                                            within three months after issuance of notice in writing to the purchaser.
                                        </li>
                                    </ul>
                                </li><br>
                                <li><strong>Notices</strong>
                                    <ul>
                                        <li type="a">The Buyer/Vendee will ensure timely payment of each
                                            Installment
                                            as
                                            per the Payment
                                            Schedule set out above to ensure uninterrupted construction and timely
                                            completion of
                                            the Project. In case any Installment is delayed from its Due Date by the
                                            Buyer/Vendee:
                                            <ul>
                                                <li type="i">The Buyer/Vendee is bound to pay all remaining
                                                    installments/outstanding in accordance with the Payment Schedule set
                                                    out
                                                    in
                                                    Schedule-B to avoid the defection in payment plan.</li><br>
                                                <li type="i">That failing to comply with clause 10a (i) of this
                                                    agreement, the Buyer/Vendee will be served with “REMINDER NOTICE”
                                                    after
                                                    15th
                                                    day of late payment at given address and email and other whatsoever
                                                    adequate
                                                    information provided by the client in order to communicate through.
                                                </li>
                                                <br>
                                                <li type="i">That if the Buyer/Vendee do not pay the remaining
                                                    outstanding/payment of installment after receiving “REMINDER NOTICE”
                                                    according to clause 10.a (ii) of this agreement, the Seller/Vendor
                                                    shall
                                                    serve another notice citing it as “SHOW CAUSE NOTICE” on 30th day of
                                                    late
                                                    payment at given address and email and other whatsoever adequate
                                                    information
                                                    provided by the client in order to communicate through.</li><br>
                                                <li type="i">That failing to comply with clause 10a (ii) and 10a
                                                    (iii)
                                                    of this agreement the Seller/Vendor shall serve the “DEMAND NOTICE”
                                                    to
                                                    the
                                                    Buyer/Vendee on 45th day of late payment at given address and email
                                                    and
                                                    other whatsoever adequate information provided by the client in
                                                    order to
                                                    communicate through.</li><br>
                                                <li type="i">That despite serving the above three (03) notices if
                                                    the
                                                    Buyer/Vendee still do not pay remaining installment, the
                                                    Seller/Vendor
                                                    will
                                                    serve “CANCELATION NOTICE” which will take effect immediately on the
                                                    60th
                                                    day of late payment of outstanding/installment of the unit. That if
                                                    the
                                                    Buyer/Vendee responds and wills to retain the unit and pay the
                                                    remaining
                                                    installments within 15 days of “CANCELATION NOTICE” which will be
                                                    counted as
                                                    75th Day. The Buyer/Vendee will have to pay the installments
                                                    together
                                                    with
                                                    5% (five percent) Service Charges, on account of recovery expenses,
                                                    on
                                                    the
                                                    total unpaid amount of the installments.</li><br>
                                                <li type="i">That in case of termination of this agreement
                                                    because of
                                                    the
                                                    default of payment of the sale price or failing to submit
                                                    installment as
                                                    per
                                                    Payment Plan set out in Schedule-B or any other breach by the
                                                    purchaser,
                                                    Seller/Vendor shall reserve the sole right to rescind and repeal
                                                    this
                                                    agreement.</li><br>
                                                <li type="i">That in the event of Cancellation of this agreement,
                                                    stipulated under clause 10a (vi), Seller/Vendor may on its own
                                                    discretion
                                                    sell the unit to any third party and refund to the purchaser the
                                                    proceeds of
                                                    such sale or the amount received from the purchaser to date in
                                                    accordance
                                                    with Clause 9 and subject to deductions specified therein. For the
                                                    avoidance
                                                    of doubt, no payment from the Buyer/Vendee shall be accepted after
                                                    the
                                                    date
                                                    of termination, however, in the event the Buyer/Vendee deposits a
                                                    payment
                                                    after the date of termination it shall be refunded. </li><br>
                                                <li type="i">That subject to the deduction and confiscation of
                                                    above-mentioned total price of the unit, the Buyer/Vendee of any
                                                    other
                                                    individual whatsoever pertinent to the “Buyer” cannot purport and
                                                    claim
                                                    it
                                                    in any court of law or any legal forum or authority whatsoever.</li>
                                                <br>
                                                <li type="i">(i)The Parties hereby agree and acknowledge that the
                                                    late
                                                    payment charges stated in the Agreement do not constitute a penalty
                                                    and
                                                    are
                                                    a genuine pre-estimate of the losses suffered by the Seller/Vendor.
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li><br>
                                <li><strong>Force Majeure</strong>
                                    <ul>
                                        <li type="a">Neither party shall be considered to be in default or breach
                                            of
                                            an
                                            obligation under this Agreement if performance of the obligation is
                                            prevented or
                                            delayed by any Force Majeure Event, or any cause beyond the reasonable
                                            control
                                            of
                                            the Parties to this agreement provided that the affected Party gives to the
                                            other
                                            Party a written notice within thirty (30) days of such an event indicating
                                            such
                                            circumstances. However, an event of Force Majeure shall not excuse a failure
                                            by
                                            a
                                            party to make a payment as and when it falls due. A “Force Majeure” is any
                                            event
                                            or
                                            circumstance (or a combination of events and circumstances) which is beyond
                                            the
                                            reasonable control of the affected party including (but not) limited to)
                                            strikes,
                                            lockouts, fires, contamination, natural disasters, acts of God, war,
                                            terrorism,
                                            and
                                            other hostilities, invasion, sabotage, public disorders, and any action by
                                            or
                                            inaction of a Government or Governmental or juridical authority or similar
                                            body,
                                            the
                                            Master Developer or due to restrictions imposed by the Master Developer or
                                            Government. The time for performance of any obligation of either party
                                            delayed
                                            by
                                            Force Majeure shall be extended accordingly.</li>
                                    </ul>
                                </li><br>
                                <li><strong>Late Installment Payment</strong>
                                    <ul>
                                        <li type="a">The Buyer/Vendee acknowledges that time is of the essence
                                            with
                                            respect to the installments that are to be paid under the terms and
                                            conditions
                                            of
                                            this agreement. In the event that any installments are not fully paid when
                                            they
                                            are
                                            due, the Buyer/Vendee shall be required to pay the Seller/Vendor a Service
                                            charges
                                            equal to five percent (5%) on all outstanding installments.</li>
                                    </ul>
                                </li><br>
                                <li><strong>Default And Termination/Cancellation</strong>
                                    <ul>
                                        <li type="a">If the Buyer/Vendee:
                                            <ul>
                                                <li type="i">Cancels or withdraws from this Agreement; or</li>
                                                <li type="i">b.Is bankrupt or is under the process of
                                                    liquidation; or
                                                    The Seller/Vendor shall give the Buyer/Vendee a thirty (30) days’
                                                    notice
                                                    in
                                                    writing calling on the Buyer/Vendee to remedy such default and if
                                                    the
                                                    Buyer/Vendee fails to comply with such notice then the Seller/Vendor
                                                    shall
                                                    be entitled, without further notice and without prejudice to any
                                                    other
                                                    rights available under the Law, to terminate this Agreement; and it
                                                    also
                                                    Reserves the right to re-allot/re-sell the Unit.</li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li><br>
                                <li><strong>Governing Law and Dispute Resolution</strong>
                                    <ul>
                                        <li type="a">This agreement will be governed by and construed in
                                            accordance
                                            with
                                            the prevailing laws of the Islamic Republic of Pakistan.</li><br>
                                        <li type="a">All disputes between the parties in relation to or arising
                                            from
                                            the
                                            agreement shall be referred to arbitration in Islamabad to be conducted in
                                            accordance with Arbitration Act, 1940. An award given by the arbitrator
                                            shall be
                                            final and binding on the parties.</li>
                                    </ul>
                                </li>
                            </ol>

                        </div>
                    </div>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    <button class="btn btn-relief-outline-primary waves-effect waves-float waves-light btn-prev"
                        type="button">
                        <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                        <span class="align-middle d-sm-inline-block d-none">Previous</span>
                    </button>
                    <button class="btn btn-relief-outline-primary waves-effect waves-float waves-light btn-next"
                        type="button">
                        <span class="align-middle d-sm-inline-block d-none">Next</span>
                        <i data-feather="arrow-right" class="align-middle ms-sm-25 ms-0"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="card content shadow-none m-0" id="sales-plan" role="tabpanel"
            aria-labelledby="sales-plan-trigger">

            <div class="card-body">

                <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                    <div class="card-header">
                        <h3>1. PRIMARY DATA</h3>
                    </div>

                    <div class="card-body">
                        <div class="row mb-1">
                            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                <label class="form-label fs-5" for="unit_no">Unit No</label>
                                <input type="text" class="form-control form-control-lg" id="unit_no"
                                    placeholder="Unit No" value="{{ $unit->floor_unit_number }}" disabled />
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                <label class="form-label fs-5" for="floor_no">Floor No</label>
                                <input type="text" class="form-control form-control-lg" id="floor_no"
                                    placeholder="Floor No" value="{{ $unit->floor->short_label }}" disabled />
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                <label class="form-label fs-5" for="unit_type">Unit Type</label>
                                <input type="text" class="form-control form-control-lg" id="unit_type"
                                    placeholder="Unit Type" value="{{ $unit->type->name }}" disabled />
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                <label class="form-label fs-5" for="unit_size">Unit Size(sq.ft)</label>
                                <input type="text" class="form-control form-control-lg" id="unit_size"
                                    placeholder="Unit Size(sq.ft)" value="{{ $unit->gross_area }}" disabled />
                            </div>
                        </div>

                        {{-- PRICING --}}
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                                <div class="card m-0"
                                    style="border: 2px solid #eee; border-style: dashed; border-radius: 0;">
                                    <div class="card-header">
                                        <h3>PRICING</h3>
                                    </div>

                                    <div class="card-body">
                                        {{-- Unit Rate Row --}}
                                        <div class="row mb-1" id="div-unit">
                                            <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                                <label class="form-label fs-5" for="unit_price">Unit Price
                                                    (Rs)</label>
                                                <input type="text" class="form-control form-control-lg"
                                                    id="unit_price" placeholder="Unit Price"
                                                    value="{{ number_format($salesPlan->unit_price, 2) }}" disabled />
                                            </div>

                                            <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                                <label class="form-label fs-5" for="total-price-unit">Amount
                                                    (Rs)</label>
                                                <input type="text" class="form-control form-control-lg" disabled
                                                    id="total-price-unit" placeholder="Amount"
                                                    value="{{ number_format($salesPlan->unit_price * $unit->gross_area) }}" />
                                            </div>
                                        </div>

                                        <div id="div_additional_cost">

                                            @php
                                                $total_additional_cost = 0;
                                            @endphp

                                            @foreach ($salesPlan->additionalCosts as $key => $additionalCost)
                                                @php
                                                    $total_additional_cost += $additionalCost->pivot->amount;
                                                @endphp

                                                <div class="row mb-1">
                                                    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                                        <label class="form-label fs-5"
                                                            for="price-{{ $additionalCost->slug }}-{{ $key }}">{{ $additionalCost->name }}
                                                            (%)
                                                        </label>

                                                        <input type="number" class="form-control form-control-lg"
                                                            id="percentage-{{ $additionalCost->slug }}-{{ $key }}"
                                                            placeholder="{{ $additionalCost->name }}" disabled
                                                            value="{{ $additionalCost->pivot->percentage }}" />

                                                    </div>

                                                    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                                        <label class="form-label fs-5"
                                                            for="total-price-{{ $additionalCost->slug }}-{{ $key }}">Amount
                                                            (Rs)</label>

                                                        <input type="text" class="form-control form-control-lg"
                                                            id="total-price-{{ $additionalCost->slug }}-{{ $key }}"
                                                            disabled placeholder="Amount"
                                                            value="{{ number_format($additionalCost->pivot->amount, 2) }}" />
                                                    </div>
                                                </div>
                                            @endforeach

                                        </div>

                                        {{-- Discount Row --}}
                                        <div class="row mb-1" id="div-discount">
                                            <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                                <label class="form-label fs-5" for="percentage-discount">Discount
                                                    (%)</label>
                                                <input type="number" class="form-control form-control-lg"
                                                    id="percentage-discount" placeholder="Discount %" disabled
                                                    value="{{ $salesPlan->discount_percentage }}" />
                                            </div>

                                            <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                                <label class="form-label fs-5" for="total-price-discount">Amount
                                                    (Rs)</label>
                                                <input type="text" class="form-control form-control-lg" disabled
                                                    id="total-price-discount" placeholder="Discount"
                                                    value="{{ number_format($salesPlan->discount_total) }}" />
                                            </div>
                                        </div>

                                        {{-- Total Amount Row --}}
                                        <div class="row mb-1">
                                            <div class="col-lg-6 col-md-6 col-sm-6 position-relative">

                                            </div>

                                            <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                                <hr>
                                                <label class="form-label fw-bolder fs-5" for="unit_rate_total">Total
                                                    (Rs)</label>
                                                <input type="text" class="form-control form-control-lg"
                                                    id="unit_rate_total" placeholder="Total"
                                                    value="{{ number_format($salesPlan->total_price, 2) }}"
                                                    disabled />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- PAYMENT PLAN --}}
                <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;"
                    id="installments_acard">
                    <div class="card-header">
                        <h3>2. INSTALLMENT DETAILS</h3>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped table-borderless"
                                        id="installments_table" style="position: relative;">
                                        <thead style="position: sticky; top: 0; z-index: 10;">
                                            <tr class="text-center">
                                                <th scope="col">#</th>
                                                <th scope="col">Installments</th>
                                                <th scope="col">Due Date</th>
                                                <th scope="col">Total Amount</th>
                                                {{-- <th scope="col">Remarks</th> --}}
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @forelse ($salesPlan->installments as $installment)
                                                <tr class="text-center">
                                                    <td>{{ $installment->installment_order }}</td>
                                                    <td>{{ $installment->details }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($installment->date)->format('F j, Y') }}
                                                    </td>
                                                    <td>{{ number_format($installment->amount, 2) }}</td>
                                                    {{-- <td>{{ $installment->remarks }}</td> --}}
                                                </tr>
                                            @empty
                                                <tr class="text-center">
                                                    <td colspan="5">No data found</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    <button class="btn btn-relief-outline-primary waves-effect waves-float waves-light btn-prev"
                        type="button">
                        <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                        <span class="align-middle d-sm-inline-block d-none">Previous</span>
                    </button>
                    <button class="btn btn-relief-outline-primary waves-effect waves-float waves-light btn-next"
                        type="button">
                        <span class="align-middle d-sm-inline-block d-none">Next</span>
                        <i data-feather="arrow-right" class="align-middle ms-sm-25 ms-0"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="card content shadow-none m-0" id="receipts" role="tabpanel" aria-labelledby="receipts-trigger">

            <div class="card-body">
                {{-- PAYMENT PLAN --}}
                <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;"
                    id="installments_acard">
                    <div class="card-header">
                        <h3>Receipts</h3>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped table-borderless"
                                        id="installments_table" style="position: relative;">
                                        <thead style="position: sticky; top: 0; z-index: 10;">
                                            <tr class="text-center">
                                                <th scope="col">#</th>
                                                <th scope="col">Installments</th>
                                                <th scope="col">Transaction Date</th>
                                                <th scope="col">Total Amount</th>
                                                <th scope="col">Mode of Payment</th>
                                                {{-- <th scope="col">Status</th> --}}
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @php
                                                $receipts = collect($salesPlan->receipts)
                                                    ->sortBy('created_date')
                                                    ->values()
                                                    ->all();
                                            @endphp

                                            @forelse ($receipts as $receipt)
                                                <tr class="text-center">
                                                    <td>{{ $loop->index + 1 }}</td>

                                                    @php
                                                        $installmentsInfo = implode(', ', json_decode($receipt->installment_number));
                                                    @endphp
                                                    <td>{{ $installmentsInfo }}</td>

                                                    <td>{!! editDateColumn($receipt->created_date) !!}</td>
                                                    <td>{{ number_format($receipt->amount_in_numbers, 2) }}</td>
                                                    <td>{{ $receipt->mode_of_payment }}</td>
                                                    {{-- <td>
                                                        @if ($receipt->status == 1)
                                                            <span class="badge badge-glow bg-success">Active</span>
                                                        @else
                                                            <span class="badge badge-glow bg-danger"></span>
                                                        @endif
                                                    </td> --}}
                                                </tr>
                                            @empty
                                                <tr class="text-center">
                                                    <td colspan="5">No data found</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    <button class="btn btn-relief-outline-primary waves-effect waves-float waves-light btn-prev"
                        type="button">
                        <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                        <span class="align-middle d-sm-inline-block d-none">Previous</span>
                    </button>
                    <button class="btn btn-relief-outline-primary waves-effect waves-float waves-light btn-next"
                        type="button">
                        <span class="align-middle d-sm-inline-block d-none">Next</span>
                        <i data-feather="arrow-right" class="align-middle ms-sm-25 ms-0"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="card content shadow-none m-0" id="booking-form" role="tabpanel"
            aria-labelledby="booking-form-trigger">

            <div class="card-body">

                <div class="row mb-1">
                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                        <div class="card m-0"
                            style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                            <div class="card-header">
                                <h3>Sales Person</h3>
                            </div>

                            <div class="card-body">

                                <div class="row mb-1">
                                    <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                                        <label class="form-label fs-5" for="sales_source_full_name">Sales
                                            Person</label>
                                        <input type="text" class="form-control form-control-lg"
                                            id="sales_source_full_name" name="sales_source[full_name]"
                                            placeholder="Sales Person" value="{{ $user->name }}" disabled />
                                    </div>

                                    <div class="col-lg-3 col-md-3 col-sm-3 position-relative">

                                        @php
                                            $roles = $user->roles->pluck('name')->toArray();
                                            $roles = implode(', ', $roles);
                                        @endphp

                                        <label class="form-label fs-5" for="sales_source_status">Status</label>
                                        <input type="text" class="form-control form-control-lg"
                                            id="sales_source_status" name="sales_source[status]" placeholder="Status"
                                            value="{{ $roles }}" disabled />
                                    </div>

                                    <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                                        <label class="form-label fs-5" for="sales_source_contact_no">Contact
                                            No</label>
                                        <input type="text" class="form-control form-control-lg"
                                            id="sales_source_contact_no" name="sales_source[contact_no]"
                                            placeholder="Contact No" value="{{ $user->phone_no }}" disabled />
                                        {{-- invalid-tooltip">{{ $message }}
                                    </div> --}}
                                    </div>

                                    <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                                        <label class="form-label fs-5" for="sales_source_lead_source">Lead
                                            Source</label>
                                        <input type="text" class="form-control form-control-lg"
                                            id="sales_source_lead_source" placeholder="Lead Source"
                                            value="{{ $salesPlan->leadSource->name }}" disabled />
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                {{-- Stakeholder Data --}}
                <div class="row mb-1">
                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                        <div class="card m-0"
                            style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                            <div class="card-header">
                                <h3>Customer</h3>
                            </div>

                            <div class="card-body">

                                <div class="row g-1 mb-1">
                                    <input type="hidden" name="application_form[stakeholder_id]"
                                        value="{{ $customer->id }}">

                                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                        <label class="form-label fs-5" for="customer_name">Name</label>
                                        <input type="text" class="form-control form-control-lg" id="customer_name"
                                            placeholder="Name" value="{{ $customer->full_name }}" disabled />
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                        <label class="form-label fs-5" for="customer_father_name">Father/Husband
                                            Name</label>
                                        <input type="text" class="form-control form-control-lg"
                                            id="customer_father_name" value="{{ $customer->father_name }}"
                                            placeholder="Father/Husband Name" disabled />
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                        <label class="form-label fs-5" for="customer_cnic">CNIC/Passport</label>
                                        <input type="text" class="form-control form-control-lg" id="customer_cnic"
                                            placeholder="CNIC/Passport"
                                            value="{{ cnicFormat($customer->cnic) ?? '-' }}" disabled />
                                    </div>
                                </div>

                                <div class="row g-1 mb-1">
                                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                        <label class="form-label fs-5" for="customer_ntn">NTN Number</label>
                                        <input type="text" class="form-control form-control-lg" id="customer_ntn"
                                            placeholder="NTN Number" value="{{ $customer->ntn ?? '-' }}" disabled />
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                        <label class="form-label fs-5" for="customer_phone">Cell</label>
                                        <input type="text" class="form-control form-control-lg"
                                            id="customer_phone" placeholder="Cell"
                                            value="{{ $customer->contact ?? '-' }}" disabled />
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                        <label class="form-label fs-5" for="customer_occupation">Occupation</label>
                                        <input type="text" class="form-control form-control-lg"
                                            id="customer_occupation" placeholder="Occupation"
                                            value="{{ $customer->occupation ?? '-' }}" disabled />
                                    </div>
                                </div>

                                <div class="row g-1 mb-1">
                                    <div class="col-lg-6 col-md-6 col-sm-12 position-relative">
                                        <label class="form-label fs-5" for="customer_address">Address</label>
                                        <input type="text" class="form-control form-control-lg"
                                            id="customer_address" placeholder="Address"
                                            value="{{ $customer->address ?? '-' }}" disabled />
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-12 position-relative">
                                        <label class="form-label fs-5" for="customer_comments">Comments</label>
                                        <input type="text" class="form-control form-control-lg"
                                            id="customer_comments" placeholder="Comments"
                                            value="{{ $customer->comments ?? '-' }}" disabled />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- PAYMENT PLAN --}}
                <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;"
                    id="installments_acard">
                    <div class="card-header">
                        {{-- <h3>INSTALLMENT DETAILS</h3> --}}
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped table-borderless"
                                        id="installments_table" style="position: relative;">
                                        <thead style="position: sticky; top: 0; z-index: 10;">

                                            <tr class="text-center">
                                                <th style="vertical-align: middle;" rowspan="2" scope="col">Sr
                                                </th>
                                                <th style="vertical-align: middle;" rowspan="2" scope="col">
                                                    Unit #
                                                </th>
                                                <th style="vertical-align: middle;" rowspan="2" scope="col">
                                                    Area
                                                </th>
                                                <th style="vertical-align: middle;" rowspan="2" scope="col">
                                                    Rate
                                                </th>

                                                @if (isset($salesPlan->additionalCosts[0]))
                                                    @foreach ($salesPlan->additionalCosts as $additionalCosts)
                                                        <th style="vertical-align: middle;" class="text-nowrap"
                                                            scope="col">
                                                            {{ $additionalCosts->name }}
                                                        </th>
                                                    @endforeach
                                                @else
                                                    <th style="vertical-align: middle;" scope="col">Face Charges
                                                    </th>
                                                @endif


                                                <th style="vertical-align: middle;" scope="col">Discount</th>
                                                <th style="vertical-align: middle;" scope="col">Total</th>
                                                <th style="vertical-align: middle;" scope="col">Downpayment</th>
                                            </tr>

                                            <tr class="text-center">
                                                @if (isset($salesPlan->additionalCosts[0]))
                                                    @foreach ($salesPlan->additionalCosts as $additionalCosts)
                                                        <th style="vertical-align: middle;" scope="col">
                                                            {{ $additionalCosts->unit_percentage }}%
                                                        </th>
                                                    @endforeach
                                                @else
                                                    <th style="vertical-align: middle;" scope="col">
                                                        %
                                                    </th>
                                                @endif

                                                <th style="vertical-align: middle;" scope="col">
                                                    {{ $salesPlan->discount_percentage }} %</th>
                                                <th style="vertical-align: middle;" scope="col">Value</th>
                                                <th style="vertical-align: middle;" scope="col">
                                                    {{ $salesPlan->down_payment_percentage }} %</th>
                                            </tr>

                                        </thead>

                                        <tbody>
                                            <tr class="text-center">
                                                <td>1</td>
                                                <td>{{ $unit->unit_number }}</td>
                                                <td>{{ $unit->gross_area }}</td>
                                                <td>{{ number_format($unit->price_sqft, 2) }}</td>
                                                @if (isset($salesPlan->additionalCosts[0]))
                                                    @foreach ($salesPlan->additionalCosts as $additionalCosts)
                                                        <td>
                                                            {{ number_format(($additionalCosts->unit_percentage / 100) * ($salesPlan->unit_price * $unit->gross_area)) }}
                                                        </td>
                                                    @endforeach
                                                @else
                                                    <td>-</td>
                                                @endif
                                                <td>{{ number_format($salesPlan->discount_total, 2) }}</td>
                                                <td>{{ number_format($salesPlan->total_price, 2) }}</td>
                                                <td>{{ number_format($salesPlan->down_payment_total, 2) }}</td>
                                            </tr>
                                            <tr class="text-center">
                                                <td colspan="4"></td>
                                                @if (isset($salesPlan->additionalCosts[0]))
                                                    @foreach ($salesPlan->additionalCosts as $additionalCosts)
                                                        <td>
                                                            {{ number_format(($additionalCosts->unit_percentage / 100) * ($salesPlan->unit_price * $unit->gross_area)) }}
                                                        </td>
                                                    @endforeach
                                                @else
                                                    <td>-</td>
                                                @endif
                                                <td>{{ number_format($salesPlan->discount_total, 2) }}</td>
                                                <td>{{ number_format($salesPlan->total_price, 2) }}</td>
                                                <td>{{ number_format($salesPlan->down_payment_total, 2) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;"
                    id="installments_acard">
                    <div class="card-header">
                        <h3>Deal Type</h3>
                    </div>

                    <div class="card-body">

                        <div class="row g-1">
                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="ideal-deal-check"
                                        name="application_form[deal_type]" value="ideal-deal"
                                        @if (isset($customer_file)) disabled @else checked @endif
                                        @if (isset($customer_file) && $customer_file->deal_type == 'ideal-deal') checked @endif>
                                    <label class="form-check-label" for="ideal-deal-check">Idea Deal</label>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="mark-down-check"
                                        name="application_form[deal_type]" value="mark_down"
                                        @if (isset($customer_file)) disabled @endif
                                        @if (isset($customer_file) && $customer_file->deal_type == 'mark_down') checked @endif>
                                    <label class="form-check-label" for="mark-down-check">Mark Down</label>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="special-case-check"
                                        name="application_form[deal_type]" value="special_case"
                                        @if (isset($customer_file)) disabled @endif
                                        @if (isset($customer_file) && $customer_file->deal_type == 'special_case') checked @endif>
                                    <label class="form-check-label" for="special-case-check">Special Case</label>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="adjustment-check"
                                        name="application_form[deal_type]" value="adjustment"
                                        @if (isset($customer_file)) disabled @endif
                                        @if (isset($customer_file) && $customer_file->deal_type == 'adjustment') checked @endif>
                                    <label class="form-check-label" for="adjustment-check">Adjustment</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    <button class="btn btn-relief-outline-primary waves-effect waves-float waves-light btn-prev"
                        type="button">
                        <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                        <span class="align-middle d-sm-inline-block d-none">Previous</span>
                    </button>
                    @if (!isset($customer_file))
                        @can('sites.file-managements.customers.units.files.store')
                            <button class="btn btn-relief-outline-success waves-effect waves-float waves-light btn-next"
                                type="submit">
                                <span class="align-middle d-sm-inline-block d-none">Save</span>
                            </button>
                        @endcan
                    @endif
                </div>
            </div>
        </div>

    </div>

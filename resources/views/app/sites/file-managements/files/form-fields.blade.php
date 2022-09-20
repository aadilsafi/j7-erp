<div class="bs-stepper wizard-modern modern-wizard-example">

    <div class="bs-stepper-header">

        <div class="step" data-target="#applicaiton-form" role="tab" id="applicaiton-form-trigger">
            <button type="button" class="step-trigger">
                <span class="bs-stepper-box" class="font-medium-3">1</span>
                <span class="bs-stepper-label">
                    <span class="bs-stepper-title">Application Form</span>
                    <span class="bs-stepper-subtitle">Validate Application Form</span>
                </span>
            </button>
        </div>

        <div class="line">
            {{-- <i data-feather="chevron-right" class="font-medium-2"></i> --}}
        </div>

        <div class="step" data-target="#sales-aggrement" role="tab" id="sales-aggrement-trigger">
            <button type="button" class="step-trigger">
                <span class="bs-stepper-box" class="font-medium-3">2</span>
                <span class="bs-stepper-label">
                    <span class="bs-stepper-title">Sales Aggrement</span>
                    <span class="bs-stepper-subtitle">Validate Sales Aggrement</span>
                </span>
            </button>
        </div>

        <div class="line">
            {{-- <i data-feather="chevron-right" class="font-medium-2"></i> --}}
        </div>

        <div class="step" data-target="#sales-plan" role="tab" id="sales-plan-trigger">
            <button type="button" class="step-trigger">
                <span class="bs-stepper-box" class="font-medium-3">3</span>
                <span class="bs-stepper-label">
                    <span class="bs-stepper-title">Sales Plan</span>
                    <span class="bs-stepper-subtitle">Validate Sales Plan</span>
                </span>
            </button>
        </div>

        <div class="line">
            {{-- <i data-feather="chevron-right" class="font-medium-2"></i> --}}
        </div>

        <div class="step" data-target="#receipts" role="tab" id="receipts-trigger">
            <button type="button" class="step-trigger">
                <span class="bs-stepper-box" class="font-medium-3">4</span>
                <span class="bs-stepper-label">
                    <span class="bs-stepper-title">Receipts</span>
                    <span class="bs-stepper-subtitle">Validate Receipts</span>
                </span>
            </button>
        </div>

        <div class="line">
            {{-- <i data-feather="chevron-right" class="font-medium-2"></i> --}}
        </div>

        <div class="step" data-target="#booking-form" role="tab" id="booking-form-trigger">
            <button type="button" class="step-trigger">
                <span class="bs-stepper-box" class="font-medium-3">5</span>
                <span class="bs-stepper-label">
                    <span class="bs-stepper-title">Booking Form</span>
                    <span class="bs-stepper-subtitle">Validate Booking Form</span>
                </span>
            </button>
        </div>

        <div class="line">
            {{-- <i data-feather="chevron-right" class="font-medium-2"></i> --}}
        </div>

        <div class="step" data-target="#rebate-form" role="tab" id="rebate-form-trigger">
            <button type="button" class="step-trigger">
                <span class="bs-stepper-box" class="font-medium-3">6</span>
                <span class="bs-stepper-label">
                    <span class="bs-stepper-title">Booking Form</span>
                    <span class="bs-stepper-subtitle">Validate Booking Form</span>
                </span>
            </button>
        </div>
    </div>

    <div class="bs-stepper-content p-0" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">

        <div class="card shadow-none m-0" id="applicaiton-form" id="applicaiton-form" role="tabpanel"
            aria-labelledby="applicaiton-form-trigger">
            <div class="card-body">

                <div class="row">
                    <div class="col-lg-10 col-md-10 col-sm-12">
                        <div class="row g-1 mb-2">
                            <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                <label class="form-label fs-5" for="registration_no">Registration No</label>
                                <input type="text" class="form-control form-control-lg" id="registration_no"
                                    name="application_form[registration_no]" placeholder="Registration No" />
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                <label class="form-label fs-5" for="application_no">Application No</label>
                                <input type="text" class="form-control form-control-lg" id="application_no"
                                    name="application_form[application_no]" placeholder="Application No" />
                            </div>
                        </div>

                        {{-- Units Data --}}
                        <div class="row mb-1">
                            <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                                <div class="card m-0"
                                    style="border: 2px solid #eee; border-style: dashed; border-radius: 0;">
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

                        {{-- Stakeholder Data --}}
                        <div class="row mb-1">
                            <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                                <div class="card m-0"
                                    style="border: 2px solid #eee; border-style: dashed; border-radius: 0;">
                                    <div class="card-header">
                                        <h3>Stakeholder</h3>
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

                    </div>
                </div>

            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    <button class="btn btn-primary btn-prev">
                        <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                        <span class="align-middle d-sm-inline-block d-none">Previous</span>
                    </button>
                    <button class="btn btn-primary btn-next">
                        <span class="align-middle d-sm-inline-block d-none">Next</span>
                        <i data-feather="arrow-right" class="align-middle ms-sm-25 ms-0"></i>
                    </button>
                </div>
            </div>
        </div>
























        {{-- <div id="applicaiton-form" class="content" role="tabpanel" aria-labelledby="applicaiton-form-trigger">
            <div class="content-header">
                <h5 class="mb-0">Account Details</h5>
                <small class="text-muted">Enter Your Account Details.</small>
            </div>
            <div class="row">
                <div class="mb-1 col-md-6">
                    <label class="form-label" for="modern-username">Username</label>
                    <input type="text" id="modern-username" class="form-control" placeholder="johndoe" />
                </div>
                <div class="mb-1 col-md-6">
                    <label class="form-label" for="modern-email">Email</label>
                    <input type="email" id="modern-email" class="form-control" placeholder="john.doe@email.com"
                        aria-label="john.doe" />
                </div>
            </div>
            <div class="row">
                <div class="mb-1 form-password-toggle col-md-6">
                    <label class="form-label" for="modern-password">Password</label>
                    <input type="password" id="modern-password" class="form-control"
                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                </div>
                <div class="mb-1 form-password-toggle col-md-6">
                    <label class="form-label" for="modern-confirm-password">Confirm Password</label>
                    <input type="password" id="modern-confirm-password" class="form-control"
                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                </div>
            </div>
            <div class="d-flex justify-content-between">
                <button class="btn btn-outline-secondary btn-prev" disabled>
                    <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                    <span class="align-middle d-sm-inline-block d-none">Previous</span>
                </button>
                <button class="btn btn-primary btn-next">
                    <span class="align-middle d-sm-inline-block d-none">Next</span>
                    <i data-feather="arrow-right" class="align-middle ms-sm-25 ms-0"></i>
                </button>
            </div>
        </div> --}}
        <div id="sales-aggrement" class="content" role="tabpanel" aria-labelledby="sales-aggrement-trigger">
            <div class="content-header">
                <h5 class="mb-0">Personal Info</h5>
                <small>Enter Your Personal Info.</small>
            </div>

        </div>
        <div id="sales-plan" class="content" role="tabpanel" aria-labelledby="sales-plan-trigger">
            <div class="content-header">
                <h5 class="mb-0">Address</h5>
                <small>Enter Your Address.</small>
            </div>
            <div class="row">
                <div class="mb-1 col-md-6">
                    <label class="form-label" for="modern-address">Address</label>
                    <input type="text" id="modern-address" class="form-control"
                        placeholder="98  Borough bridge Road, Birmingham" />
                </div>
                <div class="mb-1 col-md-6">
                    <label class="form-label" for="modern-landmark">Landmark</label>
                    <input type="text" id="modern-landmark" class="form-control" placeholder="Borough bridge" />
                </div>
            </div>
            <div class="row">
                <div class="mb-1 col-md-6">
                    <label class="form-label" for="pincode3">Pincode</label>
                    <input type="text" id="pincode3" class="form-control" placeholder="658921" />
                </div>
                <div class="mb-1 col-md-6">
                    <label class="form-label" for="city3">City</label>
                    <input type="text" id="city3" class="form-control" placeholder="Birmingham" />
                </div>
            </div>
            <div class="d-flex justify-content-between">
                <button class="btn btn-primary btn-prev">
                    <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                    <span class="align-middle d-sm-inline-block d-none">Previous</span>
                </button>
                <button class="btn btn-primary btn-next">
                    <span class="align-middle d-sm-inline-block d-none">Next</span>
                    <i data-feather="arrow-right" class="align-middle ms-sm-25 ms-0"></i>
                </button>
            </div>
        </div>
        <div id="receipts" class="content" role="tabpanel" aria-labelledby="receipts-trigger">
            <div class="content-header">
                <h5 class="mb-0">Social Links</h5>
                <small>Enter Your Social Links.</small>
            </div>
            <div class="row">
                <div class="mb-1 col-md-6">
                    <label class="form-label" for="modern-twitter">Twitter</label>
                    <input type="text" id="modern-twitter" class="form-control"
                        placeholder="https://twitter.com/abc" />
                </div>
                <div class="mb-1 col-md-6">
                    <label class="form-label" for="modern-facebook">Facebook</label>
                    <input type="text" id="modern-facebook" class="form-control"
                        placeholder="https://facebook.com/abc" />
                </div>
            </div>
            <div class="row">
                <div class="mb-1 col-md-6">
                    <label class="form-label" for="modern-google">Google+</label>
                    <input type="text" id="modern-google" class="form-control"
                        placeholder="https://plus.google.com/abc" />
                </div>
                <div class="mb-1 col-md-6">
                    <label class="form-label" for="modern-linkedin">Linkedin</label>
                    <input type="text" id="modern-linkedin" class="form-control"
                        placeholder="https://linkedin.com/abc" />
                </div>
            </div>
            <div class="d-flex justify-content-between">
                <button class="btn btn-primary btn-prev">
                    <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                    <span class="align-middle d-sm-inline-block d-none">Previous</span>
                </button>
                <button class="btn btn-success btn-submit">Submit</button>
            </div>
        </div>
        <div id="booking-form" class="content" role="tabpanel" aria-labelledby="receipts-trigger">
            <div class="content-header">
                <h5 class="mb-0">Social Links</h5>
                <small>Enter Your Social Links.</small>
            </div>
            <div class="row">
                <div class="mb-1 col-md-6">
                    <label class="form-label" for="modern-twitter">Twitter</label>
                    <input type="text" id="modern-twitter" class="form-control"
                        placeholder="https://twitter.com/abc" />
                </div>
                <div class="mb-1 col-md-6">
                    <label class="form-label" for="modern-facebook">Facebook</label>
                    <input type="text" id="modern-facebook" class="form-control"
                        placeholder="https://facebook.com/abc" />
                </div>
            </div>
            <div class="row">
                <div class="mb-1 col-md-6">
                    <label class="form-label" for="modern-google">Google+</label>
                    <input type="text" id="modern-google" class="form-control"
                        placeholder="https://plus.google.com/abc" />
                </div>
                <div class="mb-1 col-md-6">
                    <label class="form-label" for="modern-linkedin">Linkedin</label>
                    <input type="text" id="modern-linkedin" class="form-control"
                        placeholder="https://linkedin.com/abc" />
                </div>
            </div>
            <div class="d-flex justify-content-between">
                <button class="btn btn-primary btn-prev">
                    <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                    <span class="align-middle d-sm-inline-block d-none">Previous</span>
                </button>
                <button class="btn btn-success btn-submit">Submit</button>
            </div>
        </div>
        <div id="rebate-form" class="content" role="tabpanel" aria-labelledby="receipts-trigger">
            <div class="content-header">
                <h5 class="mb-0">Social Links</h5>
                <small>Enter Your Social Links.</small>
            </div>
            <div class="row">
                <div class="mb-1 col-md-6">
                    <label class="form-label" for="modern-twitter">Twitter</label>
                    <input type="text" id="modern-twitter" class="form-control"
                        placeholder="https://twitter.com/abc" />
                </div>
                <div class="mb-1 col-md-6">
                    <label class="form-label" for="modern-facebook">Facebook</label>
                    <input type="text" id="modern-facebook" class="form-control"
                        placeholder="https://facebook.com/abc" />
                </div>
            </div>
            <div class="row">
                <div class="mb-1 col-md-6">
                    <label class="form-label" for="modern-google">Google+</label>
                    <input type="text" id="modern-google" class="form-control"
                        placeholder="https://plus.google.com/abc" />
                </div>
                <div class="mb-1 col-md-6">
                    <label class="form-label" for="modern-linkedin">Linkedin</label>
                    <input type="text" id="modern-linkedin" class="form-control"
                        placeholder="https://linkedin.com/abc" />
                </div>
            </div>
            <div class="d-flex justify-content-between">
                <button class="btn btn-primary btn-prev">
                    <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                    <span class="align-middle d-sm-inline-block d-none">Previous</span>
                </button>
                <button class="btn btn-success btn-submit">Submit</button>
            </div>
        </div>
    </div>
</div>

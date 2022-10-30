{{-- <!-- Modern Vertical Wizard -->
<section class="modern-vertical-wizard">
    <div class="bs-stepper vertical wizard-modern modern-vertical-wizard-example">
        <div class="bs-stepper-header">
            <div class="step" data-target="#salesplan-details-vertical-modern" role="tab"
                id="salesplan-details-vertical-modern-trigger">
                <button type="button" class="step-trigger">
                    <span class="bs-stepper-box">
                        <i data-feather="file-text" class="font-medium-3"></i>
                    </span>
                    <span class="bs-stepper-label">
                        <span class="bs-stepper-title">Sales Plan Approval</span>
                        <span class="bs-stepper-subtitle">Sales Plan Approval Details</span>
                    </span>
                </button>
            </div>
            <div class="step" data-target="#receipt-vertical-modern" role="tab"
                id="receipt-vertical-modern-trigger">
                <button type="button" class="step-trigger">
                    <span class="bs-stepper-box">
                        <i class="bi bi-receipt-cutoff" style="margin-bottom: 10px;"></i>
                    </span>
                    <span class="bs-stepper-label">
                        <span class="bs-stepper-title">Receipt Voucher</span>
                        <span class="bs-stepper-subtitle">Receipt Voucher Details</span>
                    </span>
                </button>
            </div>
            <div class="step" data-target="#buyback-vertical-modern" role="tab"
                id="buyback-vertical-modern-trigger">
                <button type="button" class="step-trigger">
                    <span class="bs-stepper-box">
                        <i class="bi bi-folder2" style="margin-bottom: 10px;"></i>
                    </span>
                    <span class="bs-stepper-label">
                        <span class="bs-stepper-title">Buyback</span>
                        <span class="bs-stepper-subtitle">Buyback Details</span>
                    </span>
                </button>
            </div>
            <div class="step" data-target="#refund-vertical-modern" role="tab"
                id="refund-vertical-modern-trigger">
                <button type="button" class="step-trigger">
                    <span class="bs-stepper-box">
                        <i class="bi bi-folder2" style="margin-bottom: 10px;"></i>
                    </span>
                    <span class="bs-stepper-label">
                        <span class="bs-stepper-title">Refund</span>
                        <span class="bs-stepper-subtitle">Refund Details</span>
                    </span>
                </button>
            </div>

            <div class="step" data-target="#cancellation-vertical-modern" role="tab"
                id="cancellation-vertical-modern-trigger">
                <button type="button" class="step-trigger">
                    <span class="bs-stepper-box">
                        <i class="bi bi-folder2" style="margin-bottom: 10px;"></i>
                    </span>
                    <span class="bs-stepper-label">
                        <span class="bs-stepper-title">Cancelation</span>
                        <span class="bs-stepper-subtitle">Cancelation Details</span>
                    </span>
                </button>
            </div>

            <div class="step" data-target="#title-transfer-vertical-modern" role="tab"
                id="title-transfer-vertical-modern-trigger">
                <button type="button" class="step-trigger">
                    <span class="bs-stepper-box">
                        <i class="bi bi-folder2" style="margin-bottom: 10px;"></i>
                    </span>
                    <span class="bs-stepper-label">
                        <span class="bs-stepper-title">Title transfer</span>
                        <span class="bs-stepper-subtitle">Title transfer Details</span>
                    </span>
                </button>
            </div>
        </div>
        <div class="bs-stepper-content">
            <div id="salesplan-details-vertical-modern" class="content" role="tabpanel"
                aria-labelledby="salesplan-details-vertical-modern-trigger">
                {{ $salesInvoice->table() }}



            </div>

            <div id="receipt-vertical-modern" class="content" role="tabpanel"
                aria-labelledby="receipt-vertical-modern-trigger">

            </div>

            <div id="buyback-vertical-modern" class="content" role="tabpanel"
                aria-labelledby="buyback-vertical-modern-trigger">

            </div>

            <div id="refund-vertical-modern" class="content" role="tabpanel"
                aria-labelledby="refund-vertical-modern-trigger">

            </div>

            <div id="cancellation-vertical-modern" class="content" role="tabpanel"
                aria-labelledby="cancellation-vertical-modern-trigger">

            </div>

            <div id="title-transfer-vertical-modern" class="content" role="tabpanel"
                aria-labelledby="title-transfer-vertical-modern-trigger">

            </div>

        </div>
    </div> --}}


    <div class="bs-stepper wizard-modern modern-wizard-example">
        <div class="bs-stepper-header">
            <div class="step crossed" data-target="#account-details-modern" role="tab" id="account-details-modern-trigger">
                <button type="button" class="step-trigger" aria-selected="false">
                    <span class="bs-stepper-box">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text font-medium-3"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                    </span>
                    <span class="bs-stepper-label">
                        <span class="bs-stepper-title">Sales Plan Approval</span>
                        <span class="bs-stepper-subtitle">Sales Plan Approval Details</span>
                    </span>
                </button>
            </div>
            <div class="line">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right font-medium-2"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </div>
            <div class="step active" data-target="#personal-info-modern" role="tab" id="personal-info-modern-trigger">
                <button type="button" class="step-trigger" aria-selected="true">
                    <span class="bs-stepper-box">
                        <i class="bi bi-receipt-cutoff" style="margin-bottom: 10px;"></i>
                    </span>
                    <span class="bs-stepper-label">
                        <span class="bs-stepper-title">Receipt Voucher</span>
                        <span class="bs-stepper-subtitle">Receipt Voucher Details</span>
                    </span>
                </button>
            </div>
            <div class="line">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right font-medium-2"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </div>
            <div class="step" data-target="#address-step-modern" role="tab" id="address-step-modern-trigger">
                <button type="button" class="step-trigger" aria-selected="false">
                    <span class="bs-stepper-box">
                        <i class="bi bi-folder2" style="margin-bottom: 10px;"></i>
                    </span>
                    <span class="bs-stepper-label">
                        <span class="bs-stepper-title">Buyback</span>
                        <span class="bs-stepper-subtitle">Buyback Details</span>
                    </span>
                </button>
            </div>
            <div class="line">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right font-medium-2"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </div>
            <div class="step" data-target="#social-links-modern" role="tab" id="social-links-modern-trigger">
                <button type="button" class="step-trigger" aria-selected="false">
                    <span class="bs-stepper-box">
                        <i class="bi bi-folder2" style="margin-bottom: 10px;"></i>
                    </span>
                    <span class="bs-stepper-label">
                        <span class="bs-stepper-title">Refund</span>
                        <span class="bs-stepper-subtitle">Refund Details</span>
                    </span>
                </button>
            </div>
            <div class="step" data-target="#cancellation-links-modern" role="tab" id="cancellation-links-modern-trigger">
                <button type="button" class="step-trigger" aria-selected="false">
                    <span class="bs-stepper-box">
                        <i class="bi bi-folder2" style="margin-bottom: 10px;"></i>
                    </span>
                    <span class="bs-stepper-label">
                        <span class="bs-stepper-title">Cancelation</span>
                        <span class="bs-stepper-subtitle">Cancelation Details</span>
                    </span>
                </button>
            </div>
            <div class="step" data-target="#title-transfer-links-modern" role="tab" id="cancellation-links-modern-trigger">
                <button type="button" class="step-trigger" aria-selected="false">
                    <span class="bs-stepper-box">
                        <i class="bi bi-folder2" style="margin-bottom: 10px;"></i>
                    </span>
                    <span class="bs-stepper-label">
                        <span class="bs-stepper-title">Title transfer</span>
                        <span class="bs-stepper-subtitle">Title transfer Details</span>
                    </span>
                </button>
            </div>


        </div>
        <div class="bs-stepper-content">
            <div id="account-details-modern" class="content" role="tabpanel" aria-labelledby="account-details-modern-trigger">
                <div class="content-header">
                    <h5 class="mb-0">Account Details</h5>
                    <small class="text-muted">Enter Your Account Details.</small>
                </div>
                <div class="row">
                    <div class="mb-1 col-md-6">
                        <label class="form-label" for="modern-username">Username</label>
                        <input type="text" id="modern-username" class="form-control" placeholder="johndoe">
                    </div>
                    <div class="mb-1 col-md-6">
                        <label class="form-label" for="modern-email">Email</label>
                        <input type="email" id="modern-email" class="form-control" placeholder="john.doe@email.com" aria-label="john.doe">
                    </div>
                </div>
                <div class="row">
                    <div class="mb-1 form-password-toggle col-md-6">
                        <label class="form-label" for="modern-password">Password</label>
                        <input type="password" id="modern-password" class="form-control" placeholder="············">
                    </div>
                    <div class="mb-1 form-password-toggle col-md-6">
                        <label class="form-label" for="modern-confirm-password">Confirm Password</label>
                        <input type="password" id="modern-confirm-password" class="form-control" placeholder="············">
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <button class="btn btn-outline-secondary btn-prev waves-effect" disabled="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left align-middle me-sm-25 me-0"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                        <span class="align-middle d-sm-inline-block d-none">Previous</span>
                    </button>
                    <button class="btn btn-primary btn-next waves-effect waves-float waves-light">
                        <span class="align-middle d-sm-inline-block d-none">Next</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right align-middle ms-sm-25 ms-0"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                    </button>
                </div>
            </div>
            <div id="personal-info-modern" class="content active dstepper-block" role="tabpanel" aria-labelledby="personal-info-modern-trigger">
                <div class="content-header">
                    <h5 class="mb-0">Personal Info</h5>
                    <small>Enter Your Personal Info.</small>
                </div>
                <div class="row">
                    <div class="mb-1 col-md-6">
                        <label class="form-label" for="modern-first-name">First Name</label>
                        <input type="text" id="modern-first-name" class="form-control" placeholder="John">
                    </div>
                    <div class="mb-1 col-md-6">
                        <label class="form-label" for="modern-last-name">Last Name</label>
                        <input type="text" id="modern-last-name" class="form-control" placeholder="Doe">
                    </div>
                </div>
                <div class="row">
                    <div class="mb-1 col-md-6">
                        <label class="form-label" for="modern-country">Country</label>
                        <div class="position-relative"><select class="select2 w-100 select2-hidden-accessible" id="modern-country" data-select2-id="modern-country" tabindex="-1" aria-hidden="true">
                            <option label=" " data-select2-id="8"></option>
                            <option>UK</option>
                            <option>USA</option>
                            <option>Spain</option>
                            <option>France</option>
                            <option>Italy</option>
                            <option>Australia</option>
                        </select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="7" style="width: auto;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-disabled="false" aria-labelledby="select2-modern-country-container"><span class="select2-selection__rendered" id="select2-modern-country-container" role="textbox" aria-readonly="true"><span class="select2-selection__placeholder">Select value</span></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span></div>
                    </div>
                    <div class="mb-1 col-md-6">
                        <label class="form-label" for="modern-language">Language</label>
                        <div class="position-relative"><select class="select2 w-100 select2-hidden-accessible" id="modern-language" multiple="" data-select2-id="modern-language" tabindex="-1" aria-hidden="true">
                            <option>English</option>
                            <option>French</option>
                            <option>Spanish</option>
                        </select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="9" style="width: auto;"><span class="selection"><span class="select2-selection select2-selection--multiple" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="-1" aria-disabled="false"><ul class="select2-selection__rendered"><li class="select2-search select2-search--inline"><input class="select2-search__field" type="search" tabindex="0" autocomplete="off" autocorrect="off" autocapitalize="none" spellcheck="false" role="searchbox" aria-autocomplete="list" placeholder="Select value" style="width: 0px;"></li></ul></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span></div>
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <button class="btn btn-primary btn-prev waves-effect waves-float waves-light">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left align-middle me-sm-25 me-0"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                        <span class="align-middle d-sm-inline-block d-none">Previous</span>
                    </button>
                    <button class="btn btn-primary btn-next waves-effect waves-float waves-light">
                        <span class="align-middle d-sm-inline-block d-none">Next</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right align-middle ms-sm-25 ms-0"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                    </button>
                </div>
            </div>
            <div id="address-step-modern" class="content" role="tabpanel" aria-labelledby="address-step-modern-trigger">
                <div class="content-header">
                    <h5 class="mb-0">Address</h5>
                    <small>Enter Your Address.</small>
                </div>
                <div class="row">
                    <div class="mb-1 col-md-6">
                        <label class="form-label" for="modern-address">Address</label>
                        <input type="text" id="modern-address" class="form-control" placeholder="98  Borough bridge Road, Birmingham">
                    </div>
                    <div class="mb-1 col-md-6">
                        <label class="form-label" for="modern-landmark">Landmark</label>
                        <input type="text" id="modern-landmark" class="form-control" placeholder="Borough bridge">
                    </div>
                </div>
                <div class="row">
                    <div class="mb-1 col-md-6">
                        <label class="form-label" for="pincode3">Pincode</label>
                        <input type="text" id="pincode3" class="form-control" placeholder="658921">
                    </div>
                    <div class="mb-1 col-md-6">
                        <label class="form-label" for="city3">City</label>
                        <input type="text" id="city3" class="form-control" placeholder="Birmingham">
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <button class="btn btn-primary btn-prev waves-effect waves-float waves-light">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left align-middle me-sm-25 me-0"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                        <span class="align-middle d-sm-inline-block d-none">Previous</span>
                    </button>
                    <button class="btn btn-primary btn-next waves-effect waves-float waves-light">
                        <span class="align-middle d-sm-inline-block d-none">Next</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right align-middle ms-sm-25 ms-0"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                    </button>
                </div>
            </div>
            <div id="social-links-modern" class="content" role="tabpanel" aria-labelledby="social-links-modern-trigger">
                <div class="content-header">
                    <h5 class="mb-0">Social Links</h5>
                    <small>Enter Your Social Links.</small>
                </div>
                <div class="row">
                    <div class="mb-1 col-md-6">
                        <label class="form-label" for="modern-twitter">Twitter</label>
                        <input type="text" id="modern-twitter" class="form-control" placeholder="https://twitter.com/abc">
                    </div>
                    <div class="mb-1 col-md-6">
                        <label class="form-label" for="modern-facebook">Facebook</label>
                        <input type="text" id="modern-facebook" class="form-control" placeholder="https://facebook.com/abc">
                    </div>
                </div>
                <div class="row">
                    <div class="mb-1 col-md-6">
                        <label class="form-label" for="modern-google">Google+</label>
                        <input type="text" id="modern-google" class="form-control" placeholder="https://plus.google.com/abc">
                    </div>
                    <div class="mb-1 col-md-6">
                        <label class="form-label" for="modern-linkedin">Linkedin</label>
                        <input type="text" id="modern-linkedin" class="form-control" placeholder="https://linkedin.com/abc">
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <button class="btn btn-primary btn-prev waves-effect waves-float waves-light">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left align-middle me-sm-25 me-0"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                        <span class="align-middle d-sm-inline-block d-none">Previous</span>
                    </button>
                    <button class="btn btn-success btn-submit waves-effect waves-float waves-light">Submit</button>
                </div>
            </div>
        </div>
    </div>

@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.file-managements.customers.units.files.create', encryptParams($site->id), encryptParams($customer->id), encryptParams($unit->id)) }}
@endsection

@section('page-title', 'Create Files')

@section('page-vendor')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/vendors/css/forms/wizard/bs-stepper.min.css">
@endsection

@section('page-css')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/plugins/forms/form-wizard.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/vendors/filepond/filepond.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.preview.min.css">
@endsection

@section('custom-css')
@endsection

@section('breadcrumbs')
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-start mb-0">Create Files</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('sites.file-managements.customers.units.files.create', encryptParams($site->id), encryptParams($customer->id), encryptParams($unit->id)) }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <form id="customer-files-create-form"
        action="{{ route('sites.file-managements.customers.units.files.store', ['site_id' => encryptParams($site->id), 'customer_id' => encryptParams($customer->id), 'unit_id' => encryptParams($unit->id)]) }}"
        method="post" class=" repeater">
        @csrf

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                @csrf

                {{ view('app.sites.file-managements.files.form-fields', [
                    'site' => $site,
                    'customer' => $customer,
                    'unit' => $unit,
                ]) }}
            </div>

            {{-- <div class="col-lg-3 col-md-3 col-sm-12 position-relative">
                <div class="sticky-md-top top-lg-100px top-md-100px top-sm-0px">
                    <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                        <div class="card-body">
                            <div class="row g-1">
                                <div class="col-md-12">

                                    <hr>

                                    <button type="submit" value="save"
                                        class="btn w-100 btn-relief-outline-success waves-effect waves-float waves-light mb-1">
                                        <i data-feather='save'></i>
                                        <span id="create_sales_plan_button_span">Save Sales Plan</span>
                                    </button>

                                    <a href="{{ route('sites.file-managements.customers.units.files.create', ['site_id' => encryptParams($site->id), 'customer_id' => encryptParams($customer->id), 'unit_id' => encryptParams($unit->id)]) }}"
                                        class="btn w-100 btn-relief-outline-danger waves-effect waves-float waves-light">
                                        <i data-feather='x'></i>
                                        {{ __('lang.commons.cancel') }}
                                    </a>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
    </form>
@endsection

@section('vendor-js')
    <script src="{{ asset('app-assets') }}/vendors/js/forms/wizard/bs-stepper.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/forms/repeater/jquery.repeater.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.preview.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.typevalidation.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.imagecrop.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.imagesizevalidation.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.filesizevalidation.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/filepond/filepond.min.js"></script>
@endsection

@section('page-js')
@endsection

@section('custom-js')

    <script type="text/javascript">
        $(document).ready(function() {
            t = document.querySelector(".modern-wizard-example");
            if (void 0 !== typeof t && null !== t) {
                var a = new Stepper(t, {
                    linear: !1
                });
                $(t).find(".btn-next").on("click", (function() {
                    a.next()
                })), $(t).find(".btn-prev").on("click", (function() {
                    a.previous()
                })), $(t).find(".btn-submit").on("click", (function() {
                    alert("Submitted..!!")
                }))
            }
        });
    </script>
@endsection

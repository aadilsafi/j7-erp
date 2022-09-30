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
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/plugins/forms/form-validation.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/vendors/filepond/filepond.min.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.preview.min.css">
@endsection

@section('custom-css')
    <style>
        .filepond--drop-label {
            color: #7367F0 !important;
        }

        .filepond--item-panel {
            background-color: #7367F0;
        }

        .filepond--panel-root {
            background-color: #e3e0fd;
        }

        /* .filepond--item {
                        width: calc(20% - 0.5em);
                    } */
    </style>
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
    <form enctype="multipart/form-data" id="customer-files-create-form"
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
                    'nextOfKin' => $nextOfKin,
                    'salesPlan' => $salesPlan,
                    'user' => $user,
                ]) }}
            </div>
        </div>
    </form>
@endsection

@section('vendor-js')
    <script src="{{ asset('app-assets') }}/vendors/js/forms/wizard/bs-stepper.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/forms/select/select2.full.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/forms/validation/jquery.validate.min.js"></script>
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
        FilePond.registerPlugin(
            FilePondPluginImagePreview,
            FilePondPluginFileValidateType,
            FilePondPluginFileValidateSize,
            FilePondPluginImageValidateSize,
            FilePondPluginImageCrop,
        );

        FilePond.create(document.getElementById('application_photo'), {
            styleButtonRemoveItemPosition: 'right',
            imageCropAspectRatio: '1:1',
            acceptedFileTypes: ['image/png', 'image/jpeg'],
            maxFileSize: '1536KB',
            ignoredFiles: ['.ds_store', 'thumbs.db', 'desktop.ini'],
            storeAsFile: true,
            maxFiles: 1,
            checkValidity: true,
            credits: {
                label: '',
                url: ''
            }
        });
        $(document).ready(function() {
            t = document.querySelector(".modern-wizard-example");
            if (void 0 !== typeof t && null !== t) {
                var a = new Stepper(t, {
                    linear: !1
                });
                $(t).find(".btn-next").on("click", (function() {
                    let registration_no = $('#registration_no').val();
                    let application_no = $('#application_no').val();
                    $('.RemoveError').empty();

                    if (registration_no.length === 0) {
                        window.scrollTo(0, 0);
                        $('#registration_no').after(
                            '<span class="text-danger RemoveError">Registration Number Required!!!</span>'
                            )
                    }
                    if (application_no.length === 0) {
                        window.scrollTo(0, 0);
                        $('#application_no').after(
                            '<span class="text-danger RemoveError">Application Number Required!!!</span>'
                            )
                    }

                    if (registration_no.length > 0 && application_no.length > 0) {
                        a.next()
                    }
                })), $(t).find(".btn-prev").on("click", (function() {
                    a.previous()
                })), $(t).find(".btn-submit").on("click", (function() {
                    alert("Submitted..!!")
                }))
            }
        });
    </script>
@endsection

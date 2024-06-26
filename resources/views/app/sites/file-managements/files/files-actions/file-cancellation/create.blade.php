@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.file-managements.files.file-cancellation.create', encryptParams($site_id)) }}
@endsection

@section('page-title', 'Create File Cancellation ')

@section('page-vendor')
@endsection

@section('page-css')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/vendors/filepond/filepond.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.preview.min.css">
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
                <h2 class="content-header-title float-start mb-0">Create File Cancellation</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('sites.file-managements.files.file-cancellation.create', encryptParams($site_id)) }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <form id="fileRefundForm" enctype="multipart/form-data"
        action="{{ route('sites.file-managements.file-cancellation.store', ['site_id' => encryptParams($site_id)]) }}"
        method="post" class="">
        @csrf
        <div class="row">
            <div id="loader" class="col-lg-9 col-md-9 col-sm-12 position-relative">
                {{ view('app.sites.file-managements.files.files-actions.file-cancellation.form-fields', [
                    'site_id' => $site_id,
                    'unit' => $unit,
                    'customer' => $customer,
                    'file' => $file,
                    'total_paid_amount' => $total_paid_amount,
                    'salesPlan' => $salesPlan,
                    'customFields' => $customFields,
                ]) }}
            </div>

            <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                <div class="card sticky-md-top top-lg-100px top-md-100px top-sm-0px"
                    style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0; z-index:10;">
                    <div class="card-body g-1">
                        <input type="hidden" name="file_id" value="{{ $file->id }}">
                        <div class="d-block mb-1">
                            <label class="form-label" style="font-size: 15px" for="doc_number">
                                Document Number
                                <span class="text-danger">*</span>
                            </label>
                            <input name="doc_number" type="text"
                                class="form-control  @error('doc_number') is-invalid @enderror" id="doc_number"
                                placeholder="Document Number" />
                            @error('doc_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-block mb-1">
                            <div class="form-check form-check-primary">
                                <input type="checkbox" checked name="checkAttachment" class="form-check-input"
                                    id="colorCheck3" value="1">
                                <label class="form-check-label" for="colorCheck3">
                                    Attachment Attached
                                </label>
                            </div>
                        </div>

                        @can('sites.file-managements.file-cancellation.store')
                            <a id="saveButton" href="#"
                                class="btn text-nowrap w-100 btn-relief-outline-success waves-effect waves-float waves-light me-1 mb-1">
                                <i data-feather='save'></i>
                                Save File Cancellation
                            </a>
                        @endcan


                        <a href="{{ route('sites.file-managements.file-cancellation.index', ['site_id' => encryptParams($site_id)]) }}"
                            class="btn w-100 btn-relief-outline-danger waves-effect waves-float waves-light">
                            <i data-feather='x'></i>
                            {{ __('lang.commons.cancel') }}
                        </a>

                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('vendor-js')
    <script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.preview.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.typevalidation.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.imagecrop.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.imagesizevalidation.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.filesizevalidation.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/filepond/filepond.min.js"></script>
    <script src="{{ asset('app-assets') }}/js/scripts/forms/form-repeater.min.js"></script>

    <script src="{{ asset('app-assets') }}/vendors/js/extensions/wNumb.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/extensions/nouislider.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/forms/spinner/jquery.bootstrap-touchspin.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/pickers/flatpickr/flatpickr.min.js"></script>

    <script src="{{ asset('app-assets') }}/vendors/js/extensions/moment.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/extensions/moment-range.min.js"></script>
    <script src="https://unpkg.com/filepond-plugin-pdf-preview/dist/filepond-plugin-pdf-preview.min.js"></script>
@endsection

@section('page-js')
    <script src="{{ asset('app-assets') }}/vendors/js/forms/validation/jquery.validate.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/forms/validation/additional-methods.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/forms/repeater/jquery.repeater.min.js"></script>
@endsection

@section('custom-js')

    <script>
        FilePond.registerPlugin(
            FilePondPluginImagePreview,
            FilePondPluginFileValidateType,
            FilePondPluginFileValidateSize,
            FilePondPluginImageValidateSize,
            FilePondPluginImageCrop,
            FilePondPluginPdfPreview,
        );

        $("#payment_due_date").flatpickr({
            defaultDate: "today",
            minDate: '{{ $salesPlan->approved_date }}',
            maxDate: 'today',
            altInput: !0,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
        });

        $(".expenses-list").repeater({
            initEmpty: true,
            show: function() {
                $(this).slideDown(), feather && feather.replace({
                    width: 14,
                    height: 14
                });
                initializeFilePond();
            },
            hide: function(e) {
                $(this).slideUp(e)
            }
        });

        function initializeFilePond() {
            const inputElements = document.querySelectorAll('input.filepond');
            console.log(inputElements.length);
            Array.from(inputElements).forEach(inputElement => {

                // create a FilePond instance at the input element location
                FilePond.create(inputElement, {
                    styleButtonRemoveItemPosition: 'right',
                    imageCropAspectRatio: '1:1',
                    acceptedFileTypes: ['image/png', 'image/jpeg', 'application/pdf'],
                    maxFileSize: '1536KB',
                    ignoredFiles: ['.ds_store', 'thumbs.db', 'desktop.ini'],
                    storeAsFile: true,
                    allowMultiple: true,
                    maxFiles: 1,
                    checkValidity: true,
                    credits: {
                        label: '',
                        url: ''
                    }
                });
            });
        }

        var validator = $("#fileRefundForm").validate({
            rules: {
                'cancellation_charges': {
                    required: true,
                },
                'payment_due_date': {
                    required: true
                },
                'amount_remarks': {
                    required: true
                },
                'amount_to_be_refunded': {
                    required: true,
                },
            },
            errorClass: 'is-invalid text-danger',
            errorElement: "span",
            wrapper: "div",
            submitHandler: function(form) {
                form.submit();
            }
        });

        $("#saveButton").click(function() {
            $("#fileRefundForm").submit();
        });

        function calculateRefundedAmount() {
            let paid_amount = '{{ $total_paid_amount }}';
            paid_amount = paid_amount.replace(/,/g, "");
            let amount_refunded = 0.0;
            let cancellationCharges = $('#cancellation_charges').val().replace(/,/g, "");
            if (cancellationCharges < parseFloat(paid_amount)) {
                amount_refunded = parseFloat(paid_amount) - cancellationCharges;
                $('#amount_to_be_refunded').val(amount_refunded.toLocaleString());
                // $('#amount_to_be_refunded').attr('value',amount_refunded);
            } else {
                toastr.error('Invalid Amount. Cancellation Charges should be less than Paid Amount',
                    "Error!", {
                        showMethod: "slideDown",
                        hideMethod: "slideUp",
                        timeOut: 2e3,
                        closeButton: !0,
                        tapToDismiss: !1,
                    });
            }
        }
    </script>
@endsection

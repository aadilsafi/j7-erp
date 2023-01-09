@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.payment-voucher.show', encryptParams($site->id)) }}
@endsection

@section('page-title', 'Payment Voucher Details')

@section('page-vendor')
@endsection


@section('page-vendor')
    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets') }}/vendors/css/tables/datatable/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets') }}/vendors/css/tables/datatable/responsive.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets') }}/vendors/css/tables/datatable/buttons.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('public_assets/admin') }}/vendors/css/forms/select/select2.min.css">
@endsection

@section('page-css')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/plugins/forms/form-validation.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/vendors/filepond/filepond.min.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.preview.min.css">
@endsection

@section('custom-css')
    <style>
        .custom_row div p {
            margin: 0;
            padding: 1rem;
            font-weight: 700;

        }

        .custom_row div input {
            margin: 0;
            padding: 1rem;
            font-weight: 700;

        }

        .custom_row {
            background-color: #f3f2f7;
        }

        .filepond--item {
            width: calc(50% - 0.5em);
        }
    </style>
@endsection


@section('breadcrumbs')
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-start mb-0">Payment Voucher Details</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('sites.payment-voucher.show', encryptParams($site->id)) }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 position-relative">

            <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                <div class="card-header">
                    <h3>1. Payment Details</h3>
                </div>

                <div class="card-body">
                    <div class="row mb-1">
                        <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                            <label class="form-label fs-5" for="amount_to_be_paid">Paid Amount</label>
                            <input type="text" class="form-control form-control-lg" id="amount_to_be_paid"
                                name="amount_to_be_paid" placeholder="Paid Amount"
                                value="{{ number_format($payment_voucher->amount_to_be_paid, 2) }}" readonly />
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                            <label class="form-label fs-5" for="description">Description</label>
                            <input type="text" class="form-control form-control-lg" id="description" name="description"
                                placeholder="Description" value="{{ $payment_voucher->description }}" readonly />
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                            <label class="form-label fs-5" for="tax_status">Tax Status</label>
                            <input type="text" class="form-control form-control-lg" id="tax_status" name="tax_status"
                                placeholder="Tax Status" value="{{ $payment_voucher->tax_status }}" readonly />
                        </div>
                    </div>

                    <div class="row mb-1">
                        <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                            <label class="form-label fs-5" for="expense_account">Expense Account</label>
                            <input type="text" class="form-control form-control-lg" id="expense_account"
                                name="expense_account" placeholder="Expense Account"
                                value="{{ $payment_voucher->expense_account }}" readonly />
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                            <label class="form-label fs-5" for="representative">Representative</label>
                            <input type="text" class="form-control form-control-lg" id="representative"
                                name="representative" placeholder="Representative"
                                value="{{ $payment_voucher->representative }}" readonly />
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                            <label class="form-label fs-5" for="business_type">Business Type</label>
                            <input type="text" class="form-control form-control-lg" id="business_type"
                                name="business_type" placeholder="Business Type"
                                value="{{ $payment_voucher->business_type }}" readonly />
                        </div>

                    </div>

                    <div class="row mb-1">
                        <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                            <label class="form-label fs-5" for="type_name">Attachment</label>
                            <input disabled id="attachment" type="file"
                                class="filepond @error('attachment') is-invalid @enderror" name="attachment[]"
                                multiple accept="image/png, image/jpeg, image/gif, application/pdf" />
                            @error('attachment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                </div>
            </div>

            <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;"
                id="stakeholders_card">
                <div class="card-header justify-content-between">
                    <h3>2. CUSTOMER DATA </h3>
                </div>
                <div class="card-body">
                    {{ view('app.sites.stakeholders.partials.stakeholder-preview-fields', ['stakeholder' => $stakeholder_data, 'hideBorders' => true]) }}
                </div>
            </div>

        </div>
    </div>

@endsection

@section('vendor-js')
    <script src="{{ asset('app-assets') }}/vendors/js/forms/repeater/jquery.repeater.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.preview.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.typevalidation.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.imagecrop.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.imagesizevalidation.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.filesizevalidation.min.js"></script>
    <script src="https://unpkg.com/filepond-plugin-pdf-preview/dist/filepond-plugin-pdf-preview.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/filepond/filepond.min.js"></script>
@endsection

@section('page-js')
    <script src="{{ asset('app-assets') }}/vendors/js/forms/validation/jquery.validate.min.js"></script>
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

        var files = [];

        @forelse($images as $image)
            files.push({
                source: '{{ $image->getUrl() }}',
            });
        @empty
        @endforelse

        FilePond.create(document.getElementById('attachment'), {
            files: files,
            styleButtonRemoveItemPosition: 'right',
            // imagePreviewMarkupShow:true,
            // stylePanelLayout:'circle',
            // styleItemPanelAspectRatio:'center',
            // imageCropAspectRatio: '5:5',
            acceptedFileTypes: ['image/png', 'image/jpeg', 'application/pdf'],
            maxFileSize: '1536KB',
            ignoredFiles: ['.ds_store', 'thumbs.db', 'desktop.ini'],
            storeAsFile: true,
            allowMultiple: true,
            // maxFiles: 2,
            checkValidity: true,
            allowPdfPreview: true,
            credits: {
                label: '',
                url: ''
            }
        });
        FilePond.setOptions({
            allowPdfPreview: true,
            pdfPreviewHeight: 320,
            pdfComponentExtraParams: 'toolbar=0&view=fit&page=1'
        });
    </script>
@endsection

@section('custom-js')
@endsection

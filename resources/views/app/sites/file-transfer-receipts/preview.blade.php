@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.file-transfer-receipts.show', encryptParams($site->id)) }}
@endsection

@section('page-title', 'Transfer Receipt Details')

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
                <h2 class="content-header-title float-start mb-0">Transfer Receipt Details</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('sites.file-transfer-receipts.show', encryptParams($site->id)) }}
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
                    <h3>2. UNIT DATA</h3>
                </div>

                <div class="card-body">
                    <div class="row mb-1">
                        <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                            <label class="form-label fs-5" for="unit_no">Unit Name</label>
                            <input type="text" class="form-control form-control-lg" id="unit_no" name="unit[no]"
                                placeholder="Unit No" value="{{ $unit_data->name }}" readonly />
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                            <label class="form-label fs-5" for="floor_no">Floor No</label>
                            <input type="text" class="form-control form-control-lg" id="floor_no" name="unit[floor_no]"
                                placeholder="Floor No" value="{{ $unit_data->floor_unit_number }}" readonly />
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                            <label class="form-label fs-5" for="unit_type">Unit Type</label>
                            <input type="text" class="form-control form-control-lg" id="unit_type" name="unit[type]"
                                placeholder="Unit Type" value="{{ $unit_data->type->name }}" readonly />
                        </div>

                    </div>

                    <div class="row mb-1">
                        <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                            <label class="form-label fs-5" for="unit_no">Unit Area(sq.ft)</label>
                            <input type="text" class="form-control form-control-lg" id="unit_no" name="unit[no]"
                                placeholder="Unit No" value="{{ number_format($unit_data->gross_area,2) }}" readonly />
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                            <label class="form-label fs-5" for="floor_no">Unit Price</label>
                            <input type="text" class="form-control form-control-lg" id="floor_no" name="unit[floor_no]"
                                placeholder="Floor No" value="{{ number_format($sales_plan->unit_price,2) }}" readonly />
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                            <label class="form-label fs-5" for="unit_type">Total Price</label>
                            <input type="text" class="form-control form-control-lg" id="unit_type" name="unit[type]"
                                placeholder="Unit Type" value="{{ number_format($sales_plan->total_price,2) }}" readonly />
                        </div>

                    </div>

                </div>
            </div>

            <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                <div class="card-header">
                    <h3>3. RECEIPT DATA</h3>
                </div>

                <div class="card-body">
                    <div class="row mb-1">

                        <div class="col-lg-9 col-md-9 col-sm-12">
                            <div class="row">

                                <div class="col-lg-6 col-md-6 col-sm-12 mb-2 position-relative">
                                    <label class="form-label fs-5" for="unit_no">Total Amount</label>
                                    <input type="text" class="form-control form-control-lg" id="unit_no"
                                        name="unit[no]" placeholder="" value="{{ number_format($receipt->amount,2) }}"
                                        readonly />
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-12 mb-2 position-relative">
                                    <label class="form-label fs-5" for="floor_no">Amount In Words</label>
                                    <input type="text" class="form-control form-control-lg" id="floor_no"
                                        name="unit[floor_no]" placeholder=""
                                        value="{{ \Str::title(numberToWords($receipt->amount)) }} Only." readonly />
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-12 mb-2 position-relative">
                                    <label class="form-label fs-5" for="unit_type">Mode Of Payment</label>
                                    <input type="text" class="form-control form-control-lg" id="unit_type"
                                        name="unit[type]" placeholder="Unit Type"
                                        value="{{ $receipt->mode_of_payment }}" readonly />
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-12 mb-2 position-relative">
                                    <label class="form-label fs-5" for="unit_type">Created At</label>
                                    <input type="text" class="form-control form-control-lg" id="unit_type"
                                        name="unit[type]" placeholder="Unit Type"
                                        value="{{ \Carbon\Carbon::parse($receipt->created_date)->format('F j, Y') }}"
                                        readonly />
                                </div>
                                @if ($receipt->mode_of_payment == 'Cheque')
                                    <div class="col-lg-6 col-md-6 col-sm-12 mb-2 position-relative">
                                        <label class="form-label fs-5" for="unit_type">Cheque Number</label>
                                        <input type="text" class="form-control form-control-lg" id="unit_type"
                                            name="unit[type]" placeholder="Cheque Number"
                                            value="{{ $receipt->cheque_no }}" readonly />
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-12 mb-2 position-relative">
                                        <label class="form-label fs-5" for="unit_type">Bank Name</label>
                                        <input type="text" class="form-control form-control-lg" id="unit_type"
                                            name="unit[type]" placeholder="Bank Name"
                                            value="{{ $receipt->bank_details }}" readonly />
                                    </div>
                                @endif

                                @if ($receipt->mode_of_payment == 'Online')
                                    <div class="col-lg-6 col-md-6 col-sm-12 mb-2 position-relative">
                                        <label class="form-label fs-5" for="unit_type">Transaction No</label>
                                        <input type="text" class="form-control form-control-lg" id="unit_type"
                                            name="unit[type]" placeholder="Transaction No"
                                            value="{{ $receipt->online_transaction_no }}" readonly />
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-12 mb-2 position-relative">
                                        <label class="form-label fs-5" for="unit_type">Transaction Date</label>
                                        <input type="text" class="form-control form-control-lg" id="unit_type"
                                            name="unit[type]" placeholder="Transaction Date"
                                            value="{{ $receipt->transaction_date }}" readonly />
                                    </div>
                                @endif

                                @if ($receipt->mode_of_payment == 'Other')
                                    <div class="col-lg-6 col-md-6 col-sm-12 mb-2 position-relative">
                                        <label class="form-label fs-5" for="unit_type">Other Payment Mode</label>
                                        <input type="text" class="form-control form-control-lg" id="unit_type"
                                            name="unit[type]" placeholder="Other Payment Mode"
                                            value="{{ $receipt->other_value }}" readonly />
                                    </div>
                                @endif

                                <div class="col-lg-12 col-md-12 col-sm-12 position-relative mt-1">
                                    <label class="form-label fs-5" for="stackholder_address">Comments</label>
                                    <textarea class="form-control  form-control-lg" readonly id="stackholder_address" placeholder="Address"
                                        rows="2">{{ $receipt->comments }}</textarea>
                                </div>

                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-ms-12">
                            <label class="form-label fs-5" for="type_name">Attachment</label>
                            <input disabled id="attachment" type="file"
                                class="filepond @error('attachment') is-invalid @enderror" name="attachment"
                                accept="image/png, image/jpeg, image/gif" />
                            @error('attachment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                </div>
            </div>

            <div id="transferOwner" class="col-lg-12 col-md-12 col-sm-12 position-relative">
                <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;"
                    id="stakeholders_card">
                    <div class="card-header justify-content-between">
                        <h3> Transfer Owner Information</h3>
                    </div>

                    <div class="card-body">
                        {{ view('app.sites.stakeholders.partials.stakeholder-preview-fields', ['stakeholder' => $transferOwner, 'hideBorders' => true]) }}
                    </div>
                </div>
            </div>

            <div id="fileOwner" class="col-lg-12 col-md-12 col-sm-12 position-relative">
                <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;"
                    id="stakeholders_card">
                    <div class="card-header justify-content-between">
                        <h3> File Owner Information</h3>
                    </div>
                    <div class="card-body">
                    {{ view('app.sites.stakeholders.partials.stakeholder-preview-fields', ['stakeholder' => $fileOwner, 'hideBorders' => true]) }}
                </div>
               
                </div>
            </div>

        </div>
    </div>

@endsection

@section('vendor-js')
    <script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.preview.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.typevalidation.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.imagecrop.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.imagesizevalidation.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.filesizevalidation.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/filepond/filepond.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/forms/repeater/jquery.repeater.min.js"></script>
    <script src="{{ asset('app-assets') }}/js/scripts/forms/form-repeater.min.js"></script>
@endsection

@section('page-js')
@endsection

@section('custom-js')
    <script>
        FilePond.registerPlugin(
            FilePondPluginImagePreview,
            FilePondPluginFileValidateType,
            FilePondPluginFileValidateSize,
            FilePondPluginImageValidateSize,
            FilePondPluginImageCrop,
        );

        var files = [];
        @if ($image != '')
            files.push({
                source: '{{ $image }}',
            });
        @endif

        FilePond.create(document.getElementById('attachment'), {
            files: files,
            styleButtonRemoveItemPosition: 'right',
            imageCropAspectRatio: '1:1',
            acceptedFileTypes: ['image/png', 'image/jpeg'],
            maxFileSize: '1536KB',
            ignoredFiles: ['.ds_store', 'thumbs.db', 'desktop.ini'],
            storeAsFile: true,
            allowMultiple: true,
            maxFiles: 2,
            minFiles: 2,
            checkValidity: true,
            credits: {
                label: '',
                url: ''
            }
        });
    </script>
@endsection

@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.receipts.show', encryptParams($site->id)) }}
@endsection

@section('page-title', 'Receipt Details')

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
                <h2 class="content-header-title float-start mb-0">Receipt Details</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('sites.receipts.show', encryptParams($site->id)) }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 position-relative">

            <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;"
                id="stakeholders_card">
                <div class="card-header justify-content-between">
                    <h3>1. CUSTOMER DATA </h3>
                </div>

                <div class="card-body">

                    <div class="row mb-1">
                        <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                            <label class="form-label fs-5" for="stackholder_full_name">Full Name</label>
                            <input type="text" readonly value="{{ $stakeholder_data->full_name }}"
                                class="form-control form-control-lg" id="stackholder_full_name" placeholder="Full Name" />
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                            <label class="form-label fs-5" for="stackholder_father_name">Father Name</label>
                            <input type="text" readonly value="{{ $stakeholder_data->father_name }}"
                                class="form-control form-control-lg" id="stackholder_father_name"
                                placeholder="Father Name" />
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                            <label class="form-label fs-5" for="stackholder_occupation">Occupation</label>
                            <input type="text" readonly value="{{ $stakeholder_data->occupation }}"
                                class="form-control form-control-lg" id="stackholder_occupation" placeholder="Occupation" />
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                            <label class="form-label fs-5" for="stackholder_designation">Designation</label>
                            <input type="text" readonly value="{{ $stakeholder_data->designation }}"
                                class="form-control form-control-lg" id="stackholder_designation"
                                placeholder="Designation" />
                        </div>
                    </div>

                    <div class="row mb-1">

                        <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                            <label class="form-label fs-5" for="stackholder_ntn">NTN</label>
                            <input type="text" readonly value="{{ $stakeholder_data->ntn }}"
                                class="form-control form-control-lg" id="stackholder_ntn" placeholder="NTN" />
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                            <label class="form-label fs-5" for="stackholder_cnic">CNIC</label>
                            <input type="text" readonly value="{{ cnicFormat($stakeholder_data->cnic) }}"
                                class="form-control form-control-lg" id="stackholder_cnic" placeholder="CNIC" />
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                            <label class="form-label fs-5" for="stackholder_contact">Contact</label>
                            <input type="text" readonly value="{{ $stakeholder_data->contact }}"
                                class="form-control form-control-lg" id="stackholder_contact" placeholder="Contact" />
                        </div>
                    </div>

                    <div class="row mb-1">
                        <div class="col-lg-6 col-md-6 col-sm-12 position-relative">
                            <label class="form-label fs-5" for="stackholder_address">Address</label>
                            <textarea class="form-control  form-control-lg" readonly id="stackholder_address" name="stackholder[address]"
                                placeholder="Address" rows="5">{{ $stakeholder_data->address }}</textarea>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12 position-relative">
                            <label class="form-label fs-5" for="stackholder_comments">Comments</label>
                            <textarea class="form-control form-control-lg" readonly id="stackholder_comments" name="stackholder[comments]"
                                placeholder="Address" rows="5">{{ $stakeholder_data->comments }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

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
                            <input type="text" class="form-control form-control-lg" id="floor_no"
                                name="unit[floor_no]" placeholder="Floor No" value="{{ $unit_data->floor_unit_number }}"
                                readonly />
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
                                placeholder="Unit No" value="{{ number_format($unit_data->gross_area) }}" readonly />
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                            <label class="form-label fs-5" for="floor_no">Unit Price</label>
                            <input type="text" class="form-control form-control-lg" id="floor_no"
                                name="unit[floor_no]" placeholder="Floor No"
                                value="{{ number_format($sales_plan->unit_price) }}" readonly />
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                            <label class="form-label fs-5" for="unit_type">Total Price</label>
                            <input type="text" class="form-control form-control-lg" id="unit_type" name="unit[type]"
                                placeholder="Unit Type" value="{{ number_format($sales_plan->total_price) }}" readonly />
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
                                    <label class="form-label fs-5" for="unit_no">Total Amount Received</label>
                                    <input type="text" class="form-control form-control-lg" id="unit_no"
                                        placeholder="" value="{{ number_format($receipt->amount_received) }}" readonly />
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-12 mb-2 position-relative">
                                    <label class="form-label fs-5" for="unit_no">Amount In Numbers</label>
                                    <input type="text" class="form-control form-control-lg" id="unit_no"
                                        name="unit[no]" placeholder=""
                                        value="{{ number_format($receipt->amount_in_numbers) }}" readonly />
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
                                        value="{{ \Carbon\Carbon::parse($receipt->created_at)->format('F j, Y') }}"
                                        readonly />
                                </div>
                                @if ($receipt->mode_of_payment == 'Cheque')
                                    <div class="col-lg-6 col-md-6 col-sm-12 mb-2 position-relative">
                                        <label class="form-label fs-5" for="unit_type">Check Number</label>
                                        <input type="text" class="form-control form-control-lg" id="unit_type"
                                            name="unit[type]" placeholder="Check Number"
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
                                            value="{{ $receipt->online_instrument_no }}" readonly />
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

                                <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                                    <label class="form-label fs-5" for="floor_no">Amount In Words</label>
                                    <input type="text" class="form-control form-control-lg" id="floor_no"
                                        name="unit[floor_no]" placeholder=""
                                        value="{{ \Str::title(numberToWords($receipt->amount_in_numbers)) }} Only."
                                        readonly />
                                </div>

                                <div class="col-lg-12 col-md-12 col-sm-12 position-relative mt-1">
                                    <label class="form-label fs-5" for="stackholder_address">Comments</label>
                                    <textarea class="form-control  form-control-lg" readonly id="stackholder_address" placeholder="Address"
                                        rows="5">{{ $receipt->comments }}</textarea>
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

            <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;"
                id="installments_acard">
                <div class="card-header">
                    <h3>4. PAID OR PARTIALLY PAID INSTALLMENT DETAILS</h3>
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
                                                @foreach ($paid_installments as $paidIntsallment)
                                                    <tr class="text-center text-nowrap">
                                                        <td>{{ $loop->index + 1 }}</td>
                                                        <td>{{ $paidIntsallment->details }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($paidIntsallment->date)->format('F j, Y') }}
                                                        </td>
                                                        <td>{{ number_format($paidIntsallment->amount) }}</td>
                                                        <td>{{ number_format($paidIntsallment->paid_amount) }}</td>
                                                        <td>{{ number_format($paidIntsallment->remaining_amount) }}</td>
                                                        <td>{{ Str::of($paidIntsallment->status)->replace('_', ' ')->title() }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;"
                id="installments_acard">
                <div class="card-header">
                    <h3>5. UNPAID INSTALLMENT DETAILS</h3>
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
                                                @isset($unpaid_installments)
                                                    @foreach ($unpaid_installments as $unPaidIntsallment)
                                                        <tr class="text-center text-nowrap">
                                                            <td>{{ $loop->index + 1 }}</td>
                                                            <td>{{ $unPaidIntsallment->details }}</td>
                                                            <td>{{ \Carbon\Carbon::parse($unPaidIntsallment->date)->format('F j, Y') }}
                                                            </td>
                                                            <td>{{ number_format($unPaidIntsallment->amount) }}</td>
                                                            <td>-</td>
                                                            <td>-</td>
                                                            <td>Unpaid</td>
                                                        </tr>
                                                    @endforeach
                                                @endisset
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

@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.settings.journal-vouchers.show', encryptParams($site_id)) }}
@endsection

@section('page-title', 'Preview Journal Voucher')

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
                <h2 class="content-header-title float-start mb-0">Preview Journal Vouchers</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('sites.settings.journal-vouchers.show', encryptParams($site_id)) }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <form class="form form-vertical" action="#" method="POST" id="journalVouchers">

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 position-relative">

                @csrf

                <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                    <div class="card-body">
                        <div class="row mb-1">
                            <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                                <label class="form-label fs-5" for="name">Voucher Number </label>
                                <input readonly type="text"
                                    class="form-control form-control-md @error('serial_number') is-invalid @enderror"
                                    id="serial_number" name="serial_number" placeholder="Journal Voucher Number"
                                    value="{{ $journal_serial_number }}" />

                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                                <label class="form-label fs-5" for="name"> JVE Number </label>
                                <input readonly type="text"
                                    class="form-control form-control-md @error('voucher_name') is-invalid @enderror"
                                    @if (isset($JournalVoucher->jve_number)) value="{{ $JournalVoucher->jve_number }}" @else value="Not Posted" @endif />

                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                                <label class="form-label fs-5" for="name"> Status</label>
                                <input type="text" readonly
                                    class="form-control form-control-md @error('voucher_name') is-invalid @enderror"
                                    id="voucher_name" name="voucher_name" placeholder="Journal Voucher Name"
                                    @if (isset($JournalVoucher)) value="{{ ucfirst($JournalVoucher->status) }}" @endif />

                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                                <label class="form-label fs-5" for="name">Remarks </label>
                                <input type="text" readonly
                                    class="form-control form-control-md @error('remarks') is-invalid @enderror"
                                    id="remarks"
                                    @if (isset($JournalVoucher)) value="{{ ucfirst($JournalVoucher->remarks) }}" @endif
                                    name="remarks" placeholder="Journal Voucher Remarks" value="" />

                            </div>
                        </div>

                        <div class="row mb-1">
                            <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                                <label class="form-label fs-5" for="name">Checked By </label>
                                <input readonly type="text"
                                    class="form-control form-control-md @error('serial_number') is-invalid @enderror"
                                    id="" name="" placeholder="Checked By"
                                    @if (isset($JournalVoucher->checked_by)) value="{{ $JournalVoucher->checkedBy->name }}" @else value="-" @endif />

                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                                <label class="form-label fs-5" for="name"> Checked Date </label>
                                <input readonly type="text"
                                    class="form-control form-control-md @error('voucher_name') is-invalid @enderror"
                                    id="user_name" name="user_name" placeholder="Checked Date"
                                    @if (isset($JournalVoucher->checked_date)) value="{{ date_format(new DateTime($JournalVoucher->checked_date), 'D d-M-Y') }}" @else value= "-" @endif />

                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                                <label class="form-label fs-5" for="name"> Posted By </label>
                                <input type="text" readonly
                                    class="form-control form-control-md @error('voucher_name') is-invalid @enderror"
                                    id="voucher_name" name="voucher_name" placeholder="Posted By"
                                    @if (isset($JournalVoucher->approved_by)) value="{{ $JournalVoucher->postedBy->name }}" @else value= "-" @endif />

                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                                <label class="form-label fs-5" for="name">Posted Date </label>
                                <input type="text" readonly
                                    class="form-control form-control-md @error('remarks') is-invalid @enderror"
                                    id="remarks"
                                    @if (isset($JournalVoucher->approved_date)) value="{{ date_format(new DateTime($JournalVoucher->approved_date), 'D d-M-Y') }}" @else value= "-" @endif
                                    placeholder="Posted Date" />

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
                {{-- Form Repeater --}}
                <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                    <div class="card-header">
                        <h3>Journal Voucher Entries ( @if (isset($JournalVoucher->jve_number))
                                {{ $JournalVoucher->jve_number }}
                            @else
                                JVE-{{ $origin_number }}
                            @endif )</h3>
                    </div>

                    <div class="card-body">
                        <table class="table table-hover table-striped table-borderless" id="installments_table"
                            style="position: relative;">
                            <thead style="position: sticky; top: 0; z-index: 10;">
                                <div class="row custom_row mb-1 text-center">
                                    <div class="col-4 text-center  position-relative">
                                        <p>ACCOUNT CODE</p>
                                    </div>

                                    <div class="col-2 position-relative">
                                        <p>DATE</p>
                                    </div>

                                    <div class="col position-relative">
                                        <p>DEBIT</p>
                                    </div>

                                    <div class="col position-relative">
                                        <p>CREDIT</p>
                                    </div>

                                    <div class="col-3 position-relative">
                                        <p>REMARKS</p>
                                    </div>
                                </div>
                            </thead>
                        </table>
                        <div class="journal-voucher-entries-list">
                            <div data-repeater-list="journal-voucher-entries">
                                <div data-repeater-item>
                                    @if (isset($JournalVoucherEntries))
                                        @foreach (isset($JournalVoucherEntries) && count($JournalVoucherEntries) ? $JournalVoucherEntries : [] as $JournalVoucherEntry)
                                            <div class="card m-0">

                                                <div>
                                                    <div>
                                                        <table class="table table-hover table-striped table-borderless"
                                                            id="installments_table" style="position: relative;">

                                                            <div>
                                                                <div>
                                                                    <div>
                                                                        <tbody id="">

                                                                            <div class="row mb-1">

                                                                                <div class="col-4 position-relative">
                                                                                    <input type="text"
                                                                                        class="form-control form-control-md @error('voucher_name') is-invalid @enderror"
                                                                                        readonly
                                                                                        @if (isset($JournalVoucherEntry)) value="{{ $JournalVoucherEntry->accountHead->name }}   {{ account_number_format($JournalVoucherEntry->account_number) }}" @endif />

                                                                                </div>

                                                                                <div class="col-2  position-relative">
                                                                                    <input type="text"
                                                                                        value=" {{ date_format(new DateTime($JournalVoucherEntry->created_date), 'D d-M-Y') }}"
                                                                                        class="form-control  form-control-md @error('voucher_name') is-invalid @enderror"
                                                                                        id="" readonly
                                                                                        value="" />
                                                                                </div>

                                                                                <div class="col position-relative">
                                                                                    <input type="number"
                                                                                        @if (isset($JournalVoucherEntry) && $JournalVoucherEntry->debit > 0) value="{{ $JournalVoucherEntry->debit }}"  @else value="0" @endif
                                                                                        class="form-control debitInput form-control-md @error('debit') is-invalid @enderror"
                                                                                        id="debit" name="debit"
                                                                                        readonly placeholder="Debit" />

                                                                                </div>
                                                                                <div class="col position-relative">
                                                                                    <input type="number"
                                                                                        @if (isset($JournalVoucherEntry) && $JournalVoucherEntry->credit > 0) value="{{ $JournalVoucherEntry->credit }}" @else value="0" @endif
                                                                                        class="form-control creditInput form-control-md @error('credit') is-invalid @enderror"
                                                                                        id="credit" name="credit"
                                                                                        readonly placeholder="Credit" />

                                                                                </div>
                                                                                <div class="col-3 position-relative">
                                                                                    <input type="text"
                                                                                        @if (isset($JournalVoucherEntry)) value="{{ ucfirst($JournalVoucherEntry->remarks) }}" @endif
                                                                                        class="form-control form-control-md @error('remarks') is-invalid @enderror"
                                                                                        id="remarks" name="remarks"
                                                                                        readonly placeholder="Remarks"
                                                                                        value="" />
                                                                                </div>


                                                                            </div>

                                                                        </tbody>
                                                                    </div>
                                                                </div>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <table class="table table-hover table-striped table-borderless" id="installments_table"
                                style="position: relative;">
                                <thead style="position: sticky; top: 0; z-index: 10;">
                                    <div class="row custom_row mb-1 text-center">
                                        <div class="col-4 text-center  position-relative">
                                            {{-- <p>Total</p> --}}
                                        </div>

                                        <div class="col-2 position-relative">
                                            <p>Total </p>
                                        </div>

                                        <div class="col position-relative">

                                            <input readonly id="total_debit" type="text" required placeholder=" Debit"
                                                name="total_debit"
                                                @if (isset($JournalVoucher)) value="{{ number_format($JournalVoucher->total_debit, 2) }}" @else value="0" @endif
                                                class="form-control form-control-md" />

                                        </div>

                                        <div class="col position-relative">
                                            <input
                                                @if (isset($JournalVoucher)) value="{{ number_format($JournalVoucher->total_credit, 2) }}" @else value="0" @endif
                                                readonly id="total_credit" type="text" required placeholder=" Credit"
                                                name="total_credit" class="form-control form-control-md" />
                                        </div>

                                        <div class="col-3 position-relative">
                                            {{-- <p>REMARKS</p> --}}
                                        </div>


                                    </div>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>


            </div>

        </div>
    </form>
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

    <script>
        $(document).ready(function() {
            $("#created_date").flatpickr({
                defaultDate: "{{ $JournalVoucher->created_date }}",
                // minDate: "today",
                altInput: !0,
                altFormat: "F j, Y",
                dateFormat: "Y-m-d",
            });

            $(".voucher_date").flatpickr({
                // defaultDate: "today",
                // minDate: "today",
                altInput: !0,
                altFormat: "F j, Y",
                dateFormat: "Y-m-d",
            });

        });
    </script>
@endsection

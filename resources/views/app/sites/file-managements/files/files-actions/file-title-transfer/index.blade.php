@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.file-managements.customers', encryptParams($site_id)) }}
@endsection

@section('page-title', 'File Title Transfer')

@section('page-vendor')
    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets') }}/vendors/css/tables/datatable/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets') }}/vendors/css/tables/datatable/responsive.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets') }}/vendors/css/tables/datatable/buttons.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets') }}/vendors/css/tables/datatable/rowGroup.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/vendors/css/pickers/flatpickr/flatpickr.min.css">

@endsection

@section('page-css')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/plugins/forms/form-validation.css">
@endsection

@section('custom-css')
@endsection

@section('breadcrumbs')
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-start mb-0">File Title Transfer</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('sites.file-managements.customers', encryptParams($site_id)) }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <p class="mb-2">

    </p>

    <div class="card" id="titleTransfer">
        <div class="card-body">
            {{-- <form action="{{ route('sites.file-managements.destroy-selected', ['site_id' => $site_id]) }}" id="file-managements-table-form" method="get"> --}}
            {{ $dataTable->table() }}
            {{-- </form> --}}

            {{-- Printing Modal --}}
            @include('app.sites.file-managements.files.partials.print-templates', [
                'fileTemplates' => $fileTemplates,
            ])

            {{-- File Approve Modal --}}
            @include('app.sites.file-managements.files.partials.file-approve-modal', [
                'chequebanks' => $banks,
            ])
        </div>
    </div>

@endsection

@section('vendor-js')
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/jquery.dataTables.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/dataTables.bootstrap5.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/dataTables.responsive.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/responsive.bootstrap5.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/datatables.checkboxes.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/datatables.buttons.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/buttons.colVis.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/jszip.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/pdfmake.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/vfs_fonts.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/buttons.html5.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/buttons.print.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/dataTables.rowGroup.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/pickers/flatpickr/flatpickr.min.js"></script>
@endsection

@section('page-js')
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/buttons.server-side.js"></script>
@endsection

@section('custom-js')
    {{ $dataTable->scripts() }}
    <script>
        function deleteSelected() {
            var selectedCheckboxes = $('.dt-checkboxes:checked').length;
            if (selectedCheckboxes > 0) {

                Swal.fire({
                    icon: 'warning',
                    title: 'Warning',
                    text: '{{ __('lang.commons.are_you_sure_you_want_to_delete_the_selected_items') }}',
                    showCancelButton: true,
                    cancelButtonText: '{{ __('lang.commons.no_cancel') }}',
                    confirmButtonText: '{{ __('lang.commons.yes_delete') }}',
                    confirmButtonClass: 'btn-danger',
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: 'btn btn-relief-outline-danger waves-effect waves-float waves-light me-1',
                        cancelButton: 'btn btn-relief-outline-success waves-effect waves-float waves-light me-1'
                    },
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#stakeholder-table-form').submit();
                    }
                });
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Warning',
                    text: '{{ __('lang.commons.please_select_at_least_one_item') }}',
                });
            }
        }

        function openTemplatesModal(file_id) {
            $('#file_id').val(file_id);
            $('#modal-sales-plan-template').modal('show');
        }

        function printTemplate(template_id) {
            let file_id = $('#file_id').val();

            let url =
                "{{ route('sites.file-managements.file-title-transfer.print', ['site_id' => encryptParams($site_id), 'file_title_transfer_id' => ':file_id', 'template_id' => ':template_id']) }}"
                .replace(':file_id', file_id)
                .replace(':template_id', template_id)

            window.open(url, '_blank').focus();

        }


        var tsite_id;
        var tcustomer_id;
        var tunit_id;
        var tfile_title_transfer_id;

        function ApproveModal(site_id, customer_id, unit_id, file_title_transfer_id) {
            $('#file-approve-modal').modal('show');
            tsite_id = site_id;
            tcustomer_id = customer_id;
            tunit_id = unit_id;
            tfile_title_transfer_id = file_title_transfer_id;
            // Swal.fire({
            //     icon: 'warning',
            //     title: 'Warning',
            //     text: 'Are You Sure You Want To Approve This Request?',
            //     showCancelButton: true,
            //     cancelButtonText: '{{ __('lang.commons.no_cancel') }}',
            //     confirmButtonText: 'Yes, Approve it!',
            //     confirmButtonClass: 'btn-danger',
            //     buttonsStyling: false,
            //     customClass: {
            //         confirmButton: 'btn btn-relief-outline-danger waves-effect waves-float waves-light me-1',
            //         cancelButton: 'btn btn-relief-outline-success waves-effect waves-float waves-light me-1'
            //     },
            // }).then((result) => {
            //     if (result.isConfirmed) {
            //         showBlockUI('#titleTransfer');
            //         let url =
            //             '{{ route('sites.file-managements.file-title-transfer.approve', ['site_id' => ':site_id', 'customer_id' => ':customer_id', 'unit_id' => ':unit_id', 'file_title_transfer_id' => ':file_title_transfer_id']) }}'
            //             .replace(':site_id', site_id).replace(':customer_id', customer_id).replace(':unit_id',
            //                 unit_id).replace(':file_title_transfer_id', file_title_transfer_id);
            //         location.href = url;
            //     }
            // });
        }
        $('.bankDiv').hide();

        $(".cheque-mode-of-payment").click(function() {
            $('#onlineValueDiv').hide();
            $('#chequeValueDiv').show();
            $('.bankDiv').show();
        });

        $(".online-mode-of-payment").click(function() {
            $('#onlineValueDiv').show();
            $('#chequeValueDiv').hide();
            $('.bankDiv').show();
        });

        $(".mode-of-payment").click(function() {
            $('#onlineValueDiv').hide();
            $('#chequeValueDiv').hide();
            $('.bankDiv').hide();
        });

        function ApproveModalRequest() {

            Swal.fire({
                icon: 'warning',
                title: 'Warning',
                text: 'Are You Sure You Want To Approve This Request?',
                showCancelButton: true,
                cancelButtonText: '{{ __('lang.commons.no_cancel') }}',
                confirmButtonText: 'Yes, Approve it!',
                confirmButtonClass: 'btn-danger',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-relief-outline-danger waves-effect waves-float waves-light me-1',
                    cancelButton: 'btn btn-relief-outline-success waves-effect waves-float waves-light me-1'
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    showBlockUI('#titleTransfer');
                    let url =
                        '{{ route('sites.file-managements.file-title-transfer.approve', ['site_id' => encryptParams($site_id), 'customer_id' => ':customer_id', 'unit_id' => ':unit_id', 'file_title_transfer_id' => ':file_title_transfer_id']) }}'
                        .replace(':customer_id', tcustomer_id).replace(':unit_id',
                            tunit_id).replace(':file_title_transfer_id', tfile_title_transfer_id);
                    location.href = url;
                }
            });
        }
        $("#transaction_date").flatpickr({
            defaultDate: 'today',
            // minDate: '',
            // altInput: !0,
            dateFormat: "Y-m-d",
        });

        var e = $(".bank");
        e.wrap('<div class="position-relative"></div>');
        e.select2({
            dropdownAutoWidth: !0,
            dropdownParent: e.parent(),
            width: "100%",
            containerCssClass: "select-lg",
        })
    </script>
@endsection

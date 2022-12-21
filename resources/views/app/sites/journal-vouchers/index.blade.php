@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.settings.journal-vouchers.index', $site_id) }}
@endsection

@section('page-title', 'Journal Vouchers')

@section('page-vendor')

    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets') }}/vendors/css/tables/datatable/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets') }}/vendors/css/tables/datatable/responsive.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets') }}/vendors/css/tables/datatable/buttons.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets') }}/vendors/css/tables/datatable/rowGroup.bootstrap5.min.css">
@endsection

@section('page-css')
@endsection

@section('custom-css')
@endsection

@section('breadcrumbs')
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-start mb-0">Journal Vouchers</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('sites.settings.journal-vouchers.index', $site_id) }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <p class="mb-2">

    </p>

    <div class="card">
        <div class="card-body">
            <form action="#" id="journal-vouchers-table-form" method="get">
                {{ $dataTable->table() }}
            </form>
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
        function addNew() {
            location.href =
                '{{ route('sites.settings.journal-vouchers.create', ['site_id' => encryptParams($site_id)]) }}';
        }

        function checkJournalVoucher(id) {

            showBlockUI('#journal-vouchers-table-form');

            Swal.fire({
                icon: 'warning',
                title: 'Warning',
                text: 'Are You Sure!!',
                showCancelButton: true,
                cancelButtonText: 'No, Cancel',
                confirmButtonText: 'Yes',
                confirmButtonClass: 'btn-success',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-relief-outline-success waves-effect waves-float waves-light me-1',
                    cancelButton: 'btn btn-relief-outline-danger waves-effect waves-float waves-light me-1'
                },
                showLoaderOnConfirm: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    showBlockUI('#journal-vouchers-table-form');

                    let url =
                        '{{ route('sites.settings.journal-vouchers.journal-vouchers-entries.check-voucher', ['site_id' => encryptParams($site_id), 'id' => ':id']) }}'
                        .replace(':id', id);
                    location.href = url;
                    hideBlockUI('#journal-vouchers-table-form');
                }
            });
            hideBlockUI('#journal-vouchers-table-form');


        }

        function postJournalVoucher(id) {

            showBlockUI('#journal-vouchers-table-form');

            Swal.fire({
                icon: 'warning',
                title: 'Warning',
                text: 'Are You Sure!!',
                showCancelButton: true,
                cancelButtonText: 'No, Cancel',
                confirmButtonText: 'Yes',
                confirmButtonClass: 'btn-success',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-relief-outline-success waves-effect waves-float waves-light me-1',
                    cancelButton: 'btn btn-relief-outline-danger waves-effect waves-float waves-light me-1'
                },
                showLoaderOnConfirm: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    showBlockUI('#journal-vouchers-table-form');

                    let url =
                        '{{ route('sites.settings.journal-vouchers.journal-vouchers-entries.post-voucher', ['site_id' => encryptParams($site_id), 'id' => ':id']) }}'
                        .replace(':id', id);
                    location.href = url;
                    hideBlockUI('#journal-vouchers-table-form');
                }
            });
            hideBlockUI('#journal-vouchers-table-form');


        }

        function revertJournalVoucher(id) {

            showBlockUI('#journal-vouchers-table-form');

            Swal.fire({
                icon: 'warning',
                title: 'Warning',
                text: 'Are You Sure!!',
                showCancelButton: true,
                cancelButtonText: 'No, Cancel',
                confirmButtonText: 'Yes',
                confirmButtonClass: 'btn-success',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-relief-outline-success waves-effect waves-float waves-light me-1',
                    cancelButton: 'btn btn-relief-outline-danger waves-effect waves-float waves-light me-1'
                },
                showLoaderOnConfirm: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    showBlockUI('#journal-vouchers-table-form');

                    let url =
                        '{{ route('sites.settings.journal-vouchers.journal-vouchers-entries.revert-voucher', ['site_id' => encryptParams($site_id), 'id' => ':id']) }}'
                        .replace(':id', id);
                    location.href = url;
                    hideBlockUI('#journal-vouchers-table-form');
                }
            });
            hideBlockUI('#journal-vouchers-table-form');


        }
    </script>
@endsection

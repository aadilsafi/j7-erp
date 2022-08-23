@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.additional-costs.index', $site_id) }}
@endsection

@section('page-title', 'Additional Costs List')

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
                <h2 class="content-header-title float-start mb-0">Additional Costs</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('sites.additional-costs.index', $site_id) }}
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
            <form action="{{ route('sites.additional-costs.destroy.selected', ['site_id' => $site_id]) }}"
                id="additional-costs-table-form" method="get">
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
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: 'btn btn-relief-outline-danger waves-effect waves-float waves-light me-1',
                        cancelButton: 'btn btn-relief-outline-success waves-effect waves-float waves-light me-1'
                    },
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#additional-costs-table-form').submit();
                    }
                });
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Warning',
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: 'btn btn-relief-outline-primary waves-effect waves-float waves-light me-1',
                    },
                    text: '{{ __('lang.commons.please_select_at_least_one_item') }}',
                });
            }
        }

        // function deleteByID(site_id, id) {
        //     Swal.fire({
        //         icon: 'warning',
        //         title: 'Warning',
        //         text: '{{ __('lang.commons.are_you_sure') }}',
        //         showCancelButton: true,
        //         cancelButtonText: '{{ __('lang.commons.no_cancel') }}',
        //         confirmButtonText: '{{ __('lang.commons.yes_delete') }}',
        //         buttonsStyling: false,
        //         customClass: {
        //             confirmButton: 'btn btn-relief-outline-danger waves-effect waves-float waves-light me-1',
        //             cancelButton: 'btn btn-relief-outline-success waves-effect waves-float waves-light me-1'
        //         },
        //     }).then((result) => {
        //         if (result.isConfirmed) {
        //             location.href =
        //                 '{{ route('sites.additional-costs.destroy', ['site_id' => ':site_id', 'id' => ':id']) }}'
        //                 .replace(':id', id).replace(':site_id', site_id);
        //         }
        //     });
        // }

        function addNew() {
            location.href = '{{ route('sites.additional-costs.create', ['site_id' => $site_id]) }}';
        }
    </script>
@endsection

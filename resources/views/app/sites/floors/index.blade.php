@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.floors.index', $site_id) }}
@endsection

@section('page-title', 'Floors List')

@section('page-vendor')
    {{-- <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets') }}/vendors/css/tables/datatable/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets') }}/vendors/css/tables/datatable/responsive.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets') }}/vendors/css/tables/datatable/buttons.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets') }}/vendors/css/tables/datatable/rowGroup.bootstrap5.min.css"> --}}

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
                <h2 class="content-header-title float-start mb-0">Floors</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('sites.floors.index', $site_id) }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <p class="mb-2">
    </p>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('sites.floors.destroy-selected', ['site_id' => $site_id]) }}"
                        id="floors-table-form" method="get">
                        <div class="table-responsive">
                            <table class="dt-complex-header table table-hover table-striped">
                                <thead>
                                    <tr class="text-center">
                                        <th rowspan="2">CHECK</th>
                                        <th rowspan="2">FLOORS</th>
                                        <th rowspan="2">ORDER</th>
                                        <th rowspan="2">AREA</th>
                                        <th rowspan="2">SHORT LABEL</th>
                                        <th rowspan="2">UNITS</th>
                                        <th colspan="5">STATUSES</th>
                                        <th rowspan="2">CREATED AT</th>
                                        <th rowspan="2" id="action">ACTIONS</th>
                                    </tr>
                                    <tr class="text-center">
                                        <th>OPEN</th>
                                        <th>SOLD</th>
                                        <th>TOKEN</th>
                                        <th>HOLD</th>
                                        <th>Partial DP</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr class="text-center">
                                        <th rowspan="2">CHECK</th>
                                        <th rowspan="2">FLOORS</th>
                                        <th rowspan="2">ORDER</th>
                                        <th rowspan="2">AREA</th>
                                        <th rowspan="2">SHORT LABEL</th>
                                        <th rowspan="2">UNITS</th>
                                        <th>OPEN</th>
                                        <th>SOLD</th>
                                        <th>TOKEN</th>
                                        <th>HOLD</th>
                                        <th>Partial DP</th>
                                        <th rowspan="2">CREATED AT</th>
                                        <th rowspan="2">ACTIONS</th>
                                    </tr>
                                    <tr class="text-center">
                                        <th colspan="5">STATUSES</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('vendor-js')
    {{-- <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/jquery.dataTables.min.js"></script>
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
    <script src="{{ asset('app-assets') }}/vendors/js/pickers/flatpickr/flatpickr.min.js"></script> --}}



    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/jquery.dataTables.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/dataTables.bootstrap5.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/dataTables.responsive.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/responsive.bootstrap5.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/datatables.checkboxes.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/datatables.buttons.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/jszip.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/pdfmake.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/vfs_fonts.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/buttons.html5.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/buttons.print.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/dataTables.rowGroup.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/pickers/flatpickr/flatpickr.min.js"></script>
@endsection

@section('page-js')
    {{-- <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/buttons.server-side.js"></script> --}}
    {{-- <script src="{{ asset('app-assets') }}/js/scripts/tables/table-datatables-basic.min.js"></script> --}}
@endsection

@section('custom-js')

    <script>
        $(document).ready(function() {

            $(".dt-complex-header").DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('sites.floors.index', ['site_id' => ':site_id']) }}'.replace(':site_id',
                        "{{ $site_id }}"),

                },
                scrollX: true,
                columns: [{
                        data: 'check',
                        name: 'check',
                    },
                    {
                        data: 'name',
                        name: 'name',
                        className: 'text-center',
                        title: 'Floors',
                    },
                    {
                        data: 'order',
                        name: 'order',
                        className: 'text-center',
                    },
                    {
                        data: 'floor_area',
                        name: 'floor_area',
                    },
                    {
                        data: 'short_label',
                        name: 'short_label',
                        className: 'text-center',
                    },
                    {
                        data: 'units_count',
                        name: 'units_count',
                        className: 'text-center',
                    },
                    {
                        data: 'units_open_count',
                        name: 'units_open_count',
                        className: 'text-center',
                    },
                    {
                        data: 'units_sold_count',
                        name: 'units_sold_count',
                        className: 'text-center',
                    },
                    {
                        data: 'units_token_count',
                        name: 'units_token_count',
                        className: 'text-center',
                    },
                    {
                        data: 'units_dp_count',
                        name: 'units_dp_count',
                        className: 'text-center',
                    },
                    {
                        data: 'units_hold_count',
                        name: 'units_hold_count',
                        className: 'text-center',
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        printable: false,
                    },

                ],
                columnDefs: [{
                    targets: 0,
                    className: 'text-center text-primary',
                    orderable: false,
                    searchable: false,
                    responsivePriority: 3,
                    render: function(data, type, full, setting) {
                        var tableRow = JSON.parse(data);
                        return '<div class=\"form-check\"> <input class=\"form-check-input dt-checkboxes\" type=\"checkbox\" value=\"' +
                            tableRow.id + '\" name=\"chkTableRow[]\" onchange="changeTableRowColor(this)" id=\"chkTableRow_' +
                            tableRow.id + '\" /><label class=\"form-check-label\" for=\"chkTableRow_' +
                            tableRow.id + '\"></label></div>';
                    },
                    checkboxes: {
                        'selectAllRender': '<div class="form-check"> <input class="form-check-input" onchange="changeAllTableRowColor()" type="checkbox" value="" id="checkboxSelectAll" /><label class="form-check-label" for="checkboxSelectAll"></label></div>',
                    }
                }],
                order: [
                    [11, 'desc']
                ],
                dom: 'BlfrtipC',
                dom: '<"card-header pt-0"<"head-label"><"dt-action-buttons text-end"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>> C<"clear">',
                buttons: [{
                        name: 'add-new',
                        text: '<i class="bi bi-plus"></i> Add New',
                        className: 'btn btn-relief-outline-primary waves-effect waves-float waves-light',
                        action: function(e, dt, node, config) {
                            location.href = '{{ route('sites.floors.create', ['site_id' => $site_id]) }}';
                        }
                    },
                    {
                        name: 'copy-floor',
                        text: '<i class="bi bi-clipboard-check"></i> Copy Floor',
                        className: 'btn btn-relief-outline-primary waves-effect waves-float waves-light',
                        action: function(e, dt, node, config) {
                            location.href = '{{ route('sites.floors.copyView', ['site_id' => $site_id]) }}';
                        }
                    },
                    {
                        extend: 'collection',
                        text: '<i class="bi bi-upload"></i> Export',
                        className: 'btn btn-relief-outline-secondary dropdown-toggle',
                        buttons: [{
                                extend: 'copy',
                                text: '<i class="bi bi-clipboard"></i> Copy',
                                className: 'dropdown-item',
                            },
                            {
                                extend: 'csv',
                                text: '<i class="bi bi-file-earmark-spreadsheet"></i> CSV',
                                className: 'dropdown-item',
                            },
                            {
                                extend: 'pdf',
                                text: '<i class="bi bi-filetype-pdf"></i> PDF',
                                className: 'dropdown-item',
                            },
                            {
                                extend: 'excel',
                                text: '<i class="bi bi-file-earmark-spreadsheet"></i>Excel',
                                className: 'dropdown-item',
                            },
                            {
                                extend: 'print',
                                text: '<i class="bi bi-printer"></i> Print',
                                className: 'dropdown-item',
                            },
                        ]
                    },
                    // {
                    //     extend: 'reset',
                    //     className: 'btn btn-relief-outline-danger',
                    // },
                    // {
                    //     extend: 'reload',
                    //     className: 'btn btn-relief-outline-primary',
                    // },
                    {
                        name: 'delete-selected',
                        text: '<i class="bi bi-trash3-fill"></i> Delete Selected',
                        className: 'btn btn-relief-outline-danger waves-effect waves-float waves-light',
                        action: function(e, dt, node, config) {
                            deleteSelected();
                        }
                    },
                ],
                displayLength: 20,
                lengthMenu: [20, 25, 50, 75, 100],
                language: {
                    paginate: {
                        previous: "&nbsp;",
                        next: "&nbsp;"
                    }
                }
            });
        });

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
                        $('#floors-table-form').submit();
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

        // function addNew() {
        //     location.href = '{{ route('sites.floors.create', ['site_id' => $site_id]) }}';
        // }

        // function copyFloor() {
        //     location.href = '{{ route('sites.floors.copyView', ['site_id' => $site_id]) }}';
        // }
    </script>
@endsection

@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.floors.index') }}
@endsection

@section('page-title', 'Floors List')

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
                <h2 class="content-header-title float-start mb-0">Floors</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('sites.floors.index') }}
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
            <form action="{{ route('sites.floors.destroy.selected', ['site_id' => $site_id]) }}"
                id="floors-table-form" method="get">
                {{--  {{ $dataTable->table() }}  --}}
                <div class="table-responsive">

                    <table
                        class="table table-light table-striped table_style floors-index-dataTable data-table "
                        id="dataTables">
                        <thead>
                            <tr>
                                <th colspan="3">Name</th>
                            </tr>
                            <tr class="text-center">
                                <td>CHECK</td>
                                <td>FLOORS</td>
                                <td>ORDER</td>
                                <td>WIDTH</td>
                                <td>LENGTH</td>
                                <td>UNITS</td>
                                <td>OPEN</td>
                                <td>SOLD</td>
                                <td>TOKEN</td>
                                <td>HOLD</td>
                                <td>Partial DP</td>
                                <td>CREATED AT</td>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>

                </div>
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
    <script>

        $(document).ready(function(){
            var table = $('.floors-index-dataTable').DataTable({
                processing: true,
                serverSide: true,
                columnDefs: [
                    {
                        targets: 0,
                        className: 'text-center text-primary',
                        width: '10%',
                        orderable : false,
                        searchable : false,
                        responsivePriority : 3,
                        render : function (data, type, full, setting) {
                            var tableRow = JSON.parse(data);
                            return '<div class=\"form-check\"> <input class=\"form-check-input dt-checkboxes\" type=\"checkbox\" value=\"' + tableRow.id + '\" name=\"chkTableRow[]\" id=\"chkTableRow_' + tableRow.id + '\" /><label class=\"form-check-label\" for=\"chkTableRow_' + tableRow.id + '\"></label></div>';
                        },
                        checkboxes : {
                            'selectAllRender' :  '<div class="form-check"> <input class="form-check-input" type="checkbox" value="" id="checkboxSelectAll" /><label class="form-check-label" for="checkboxSelectAll"></label></div>',
                        }
                    },
                ],
                ajax: {
                    url: '{{ route('sites.floors.index',['site_id'=>':site_id']) }}'.replace(':site_id',"{{ $site_id }}"),

                },
                columns: [

                    {
                        data: 'check',
                        name: 'check',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'name',
                        name: 'name',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'order',
                        name: 'order',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'width',
                        name: 'width',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'length',
                        name: 'length',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'units_count',
                        name: 'units_count',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'units_open_count',
                        name: 'units_open_count',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'units_sold_count',
                        name: 'units_sold_count',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'units_token_count',
                        name: 'units_token_count',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'units_dp_count',
                        name: 'units_dp_count',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'units_hold_count',
                        name: 'units_hold_count',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        orderable: true,
                        searchable: true
                    },

                ]
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

        function addNew() {
            location.href = '{{ route('sites.floors.create', ['site_id' => $site_id]) }}';
        }

        function copyFloor() {
            location.href = '{{ route('sites.floors.copyView', ['site_id' => $site_id]) }}';
        }

    </script>
@endsection

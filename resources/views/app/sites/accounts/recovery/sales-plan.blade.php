@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.accounts.recovery.salesPlan', encryptParams($site_id)) }}
@endsection

@section('page-title', 'Sales Plan')

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
    <style>
        .dataTable .selected {
            background-color: #E3E1FC !important;
        }
    </style>
@endsection

@section('breadcrumbs')
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-start mb-0">Sales Plan</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('sites.accounts.recovery.salesPlan', encryptParams($site_id)) }}
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
                            <table class="dt-complex-header table">
                                <thead>
                                    <tr class="text-center">
                                        <th>UNIT</th>
                                        <th>UNIT PRICE</th>
                                        <th>TOTAL PRICE</th>
                                        <th>DISCOUNT (%)</th>
                                        <th>DISCOUNT PRICE</th>
                                        <th>DOWNPAYMENT (%)</th>
                                        <th>DOWNPAYMENT PRICE</th>
                                        <th>LEAD SOURCE</th>
                                        <th>STATUS</th>
                                        <th>APPROVED AT</th>
                                        <th>CREATED AT</th>
                                    </tr>

                                </thead>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
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
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/dataTables.select.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/jszip.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/pdfmake.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/vfs_fonts.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/buttons.html5.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/buttons.print.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/dataTables.rowGroup.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/pickers/flatpickr/flatpickr.min.js"></script>
@endsection

@section('page-js')

@endsection

@section('custom-js')
    <script>
        $(document).ready(function() {
            $(".dt-complex-header").DataTable({
                processing: true,
                select: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('sites.accounts.recovery.salesPlan', ['site_id' => ':site_id']) }}'
                        .replace(':site_id', "{{ encryptParams($site_id) }}"),

                },
                scrollX: true,
                columns: [
                    {
                        data: 'floor_unit_number',
                        name: 'unit.floor_unit_number',
                        className: 'text-center text-nowrap',
                    },
                    {
                        data: 'unit_price',
                        name: 'unit_price',
                        className: 'text-center text-nowrap',
                    },
                    {
                        data: 'total_price',
                        name: 'total_price',
                        className: 'text-center text-nowrap',
                    },
                    {
                        data: 'discount_percentage',
                        name: 'discount_percentage',
                        className: 'text-center text-nowrap',
                    },
                    {
                        data: 'discount_total',
                        name: 'discount_total',
                        className: 'text-center text-nowrap',
                    },
                    {
                        data: 'down_payment_percentage',
                        name: 'down_payment_percentage',
                        className: 'text-center text-nowrap',
                    },
                    {
                        data: 'down_payment_total',
                        name: 'down_payment_total',
                        className: 'text-center text-nowrap',
                    },
                    {
                        data: 'lead_source_id',
                        name: 'leadSource.name',
                        className: 'text-center text-nowrap',
                    },
                    {
                        data: 'status',
                        name: 'status',
                        className: 'text-center text-nowrap',
                    },
                    {
                        data: 'approved_date',
                        name: 'approved_date',
                        className: 'text-center text-nowrap',
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        className: 'text-center text-nowrap',
                    },
                ],
            });
        });
    </script>
@endsection

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
                            <table class="dt-complex-header table table-bordered">
                                <thead>
                                    <tr class="text-center">
                                        <th rowspan="2" class="align-middle text-nowrap">UNIT</th>
                                        <th rowspan="2" class="align-middle text-nowrap">UNIT PRICE</th>
                                        <th rowspan="2" class="align-middle text-nowrap">TOTAL PRICE</th>
                                        <th rowspan="2" class="align-middle text-nowrap">DISCOUNT (%)</th>
                                        <th rowspan="2" class="align-middle text-nowrap">DISCOUNT PRICE</th>
                                        <th rowspan="2" class="align-middle text-nowrap">DOWNPAYMENT (%)</th>
                                        <th rowspan="2" class="align-middle text-nowrap">DOWNPAYMENT PRICE</th>
                                        <th rowspan="2" class="align-middle text-nowrap">LEAD SOURCE</th>
                                        @for ($i = 1; $i <= $max_installments; $i++)
                                            <th colspan="4" class="align-middle text-nowrap">{{ englishCounting($i) }}
                                                Installment</th>
                                        @endfor
                                        <th rowspan="2" class="align-middle text-nowrap">STATUS</th>
                                        <th rowspan="2" class="align-middle text-nowrap">APPROVED AT</th>
                                        <th rowspan="2" class="align-middle text-nowrap">CREATED AT</th>
                                    </tr>

                                    <tr class="text-center">
                                        @for ($i = 1; $i <= $max_installments; $i++)
                                            <th class="align-middle text-nowrap">Remaining Amount</th>
                                            <th class="align-middle text-nowrap">Due Date</th>
                                            <th class="align-middle text-nowrap">Paid Amount</th>
                                            <th class="align-middle text-nowrap">Paid At</th>
                                        @endfor
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
    <script src="{{ asset('app-assets') }}/vendors/js/extensions/moment.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/extensions/moment-range.min.js"></script>
@endsection

@section('page-js')

@endsection

@section('custom-js')
    <script>
        window['moment-range'].extendMoment(moment);

        $(document).ready(function() {

            var maxInstallments = parseInt("{{ $max_installments }}");

            var dataTableColumns = [{
                    name: 'unit_floor_unit_number',
                    data: 'sales_plan.unit.floor_unit_number',
                    className: 'text-center align-middle text-nowrap',
                },
                {
                    name: 'sales_plan_unit_price',
                    data: 'sales_plan.unit_price',
                    className: 'text-center align-middle text-nowrap',
                    render: function(data, type, row) {
                        return data ? numberFormat(data) : '-';
                    }
                },
                {
                    name: 'sales_plan_total_price',
                    data: 'sales_plan.total_price',
                    className: 'text-center align-middle text-nowrap',
                    render: function(data, type, row) {
                        return data ? numberFormat(data) : '-';
                    }
                },
                {
                    name: 'sales_plan_discount_percentage',
                    data: 'sales_plan.discount_percentage',
                    className: 'text-center align-middle text-nowrap',
                    render: function(data, type, row) {
                        return data ? numberFormat(data) : '-';
                    }
                },
                {
                    name: 'sales_plan_discount_total',
                    data: 'sales_plan.discount_total',
                    className: 'text-center align-middle text-nowrap',
                    render: function(data, type, row) {
                        return data ? numberFormat(data) : '-';
                    }
                },
                {
                    name: 'sales_plan_down_payment_percentage',
                    data: 'sales_plan.down_payment_percentage',
                    className: 'text-center align-middle text-nowrap',
                    render: function(data, type, row) {
                        return data ? numberFormat(data) : '-';
                    }
                },
                {
                    name: 'sales_plan_down_payment_total',
                    data: 'sales_plan.down_payment_total',
                    className: 'text-center align-middle text-nowrap',
                    render: function(data, type, row) {
                        return data ? numberFormat(data) : '-';
                    }
                },
                {
                    name: 'sales_plan_lead_source_name',
                    data: 'sales_plan.lead_source.name',
                    className: 'text-center align-middle text-nowrap',
                }
            ];

            for (let index = 1; index <= maxInstallments; index++) {
                dataTableColumns.push({
                    name: 'installment_' + index + '_remaining_amount',
                    data: 'installments.installment_' + index + '_remaining_amount',
                    className: 'text-center align-middle text-nowrap',
                    render: function(data, type, row) {
                        return data ? numberFormat(data) : '-';
                    }
                });

                dataTableColumns.push({
                    name: 'installment_' + index + '_date',
                    data: 'installments.installment_' + index + '_date',
                    className: 'text-center align-middle text-nowrap',
                    render: function(data, type, row) {
                        if (data) {
                            return moment(data).format('DD/MM/YYYY');
                        } else {
                            return '-';
                        }
                    }
                });

                dataTableColumns.push({
                    name: 'installment_' + index + '_paid_amount',
                    data: 'installments.installment_' + index + '_paid_amount',
                    className: 'text-center align-middle text-nowrap',
                    render: function(data, type, row) {
                        return data ? numberFormat(data) : '-';
                    }
                });

                dataTableColumns.push({
                    name: 'installment_' + index + '_updated_at',
                    data: 'installments.installment_' + index + '_updated_at',
                    className: 'text-center align-middle text-nowrap',
                    render: function(data, type, row) {
                        if (data) {
                            return moment(data).format('DD/MM/YYYY');
                        } else {
                            return '-';
                        }
                    }
                });
            }

            var statusArray = [
                [
                    'Pending',
                    'bg-light-warning'
                ],
                [
                    'Approved',
                    'bg-light-success'
                ],
                [
                    'Disapproved',
                    'bg-light-danger'
                ],
                [
                    'Cancelled',
                    'bg-light-danger'
                ],
            ];

            dataTableColumns.push({
                name: 'sales_plan_status',
                data: 'sales_plan.status',
                className: 'text-center align-middle text-nowrap',
                render: function(data, type, row) {
                    if (data) {
                        return '<span class="badge badge-pill ' + statusArray[data][1] + '">' +
                            statusArray[data][0] + '</span>';
                    } else { return '-'; }
                }
            }, {
                name: 'sales_plan_status',
                data: 'sales_plan.status',
                className: 'text-center align-middle text-nowrap',
                render: function(data, type, row) {
                    if (data) {
                        return '<span class="badge badge-pill ' + statusArray[data][1] + '">' +
                            statusArray[data][0] + '</span>';
                    } else { return '-'; }
                }
            }
            ,{
                name: 'sales_plan_status',
                data: 'sales_plan.status',
                className: 'text-center align-middle text-nowrap',
                render: function(data, type, row) {
                    if (data) {
                        return '<span class="badge badge-pill ' + statusArray[data][1] + '">' +
                            statusArray[data][0] + '</span>';
                    } else { return '-'; }
                }
            });

            $(".dt-complex-header").DataTable({
                processing: true,
                select: true,
                serverSide: true,
                scrollX: true,
                ajax: {
                    url: '{{ route('sites.accounts.recovery.salesPlan', ['site_id' => ':site_id']) }}'
                        .replace(':site_id', "{{ encryptParams($site_id) }}"),

                },
                columns: dataTableColumns,
            });
        });
    </script>
@endsection

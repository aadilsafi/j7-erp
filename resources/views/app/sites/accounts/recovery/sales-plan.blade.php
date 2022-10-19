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

            <form class="dt_adv_search1" id="dt_adv_search1">

                <div class="row">
                    <div class="col-lg-9 col-md-9 col-sm-12 position-relative">
                        <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                            <div class="card-body">
                                <div class="row mb-1 g-1">
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 position-relative">
                                        <label class="form-label" style="font-size: 15px" for="filter_floors">Floors</label>
                                        <select class="select2-size-lg form-select col-filter" id="filter_floors"
                                            name="filter_floors">
                                            <option value="0" selected>Select Floor</option>
                                            @foreach ($floors as $floor)
                                                <option value="{{ $floor->id }}">{{ $loop->index + 1 }} -
                                                    {{ $floor->name }} (
                                                    {{ $floor->short_label }} )</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                        <label class="form-label fs-5" for="type_name">Unit</label>
                                        <input type="text" class="form-control" id="filter_unit" name="filter_unit"
                                            placeholder="Unit" />
                                    </div>

                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 position-relative">
                                        <label class="form-label" style="font-size: 15px"
                                            for="filter_customer">Customers</label>
                                        <select class="select2-size-lg form-select col-filter" id="filter_customer"
                                            name="filter_customer">
                                            <option value="0" selected>Select Customer</option>
                                            @foreach ($stakeholders as $stakeholder)
                                                @continue(!$stakeholder->stakeholder_types->where('type', 'C')->first()->status)
                                                <option value="{{ $stakeholder['id'] }}">
                                                    {{ $loop->index + 1 }} - {{ $stakeholder['full_name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-1 g-1">
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 position-relative">
                                        <label class="form-label" style="font-size: 15px" for="filter_dealer">Dealer</label>
                                        <select class="select2-size-lg form-select col-filter" id="filter_dealer"
                                            name="filter_dealer">
                                            <option value="0" selected>Select Dealer</option>
                                            @foreach ($stakeholders as $stakeholder)
                                                @continue(!$stakeholder->stakeholder_types->where('type', 'D')->first()->status)
                                                <option value="{{ $stakeholder['id'] }}">
                                                    {{ $loop->index + 1 }} - {{ $stakeholder['full_name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 position-relative">
                                        <label class="form-label" style="font-size: 15px" for="filter_sale_source">Sale
                                            Source</label>
                                        <select class="select2-size-lg form-select col-filter" id="filter_sale_source"
                                            name="filter_sale_source">
                                            <option value="0" selected>Select Sale Source</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}">
                                                    {{ $loop->index + 1 }} - {{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 position-relative">
                                        <label class="form-label" style="font-size: 15px" for="filter_type">Unit
                                            Type</label>
                                        <select class="select2-size-lg form-select col-filter" id="filter_type"
                                            name="filter_type">
                                            <option value="0" selected>Select Unit Type</option>
                                            @foreach ($types as $type)
                                                {{-- @continue($type->parent_id == 0) --}}
                                                <option value="{{ $type->id }}">
                                                    {{ $loop->index + 1 }} - {{ $type->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-1 g-1">
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 position-relative">
                                        <label class="form-label" for="filter_generated_at">Generated At</label>
                                        <input type="text" id="filter_generated_at" name="filter_generated_at"
                                            class="form-control flatpickr-range flatpickr-input active filter_date_ranger"
                                            placeholder="YYYY-MM-DD to YYYY-MM-DD" readonly="readonly">
                                    </div>

                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 position-relative">
                                        <label class="form-label" for="filter_approved_at">Approved At</label>
                                        <input type="text" id="filter_approved_at" name="filter_approved_at"
                                            class="form-control flatpickr-range flatpickr-input active filter_date_ranger"
                                            placeholder="YYYY-MM-DD to YYYY-MM-DD" readonly="readonly">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-lg-3 col-md-3 col-sm-12 position-relative">
                        <div class="sticky-md-top top-lg-100px top-md-100px top-sm-0px" style="z-index: auto;">
                            <div class="card"
                                style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                                <div class="card-body">
                                    <div class="row g-1">
                                        <div class="col-md-12">
                                            <button type="submit" value="asd" name="apply_filter"
                                                class="btn btn-relief-outline-success w-100 waves-effect waves-float waves-light buttonToBlockUI me-1">
                                                <i data-feather='save'></i>
                                                Apply Filter
                                            </button>
                                        </div>
                                        <div class="col-md-12">
                                            <button onclick="resetFilter()"
                                                class="btn btn-relief-outline-danger w-100 waves-effect waves-float waves-light"
                                                type="reset">
                                                <i data-feather='x'></i>Reset</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </form>


            <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;"
                id="table-card">
                <div class="card-body">
                    <form action="{{ route('sites.floors.destroy-selected', ['site_id' => $site_id]) }}"
                        id="floors-table-form" method="get">
                        <div class="table-responsive">
                            <table class="dt-complex-header table">
                                <thead>
                                    <tr class="text-center">
                                        <th rowspan="2" class="align-middle text-nowrap">FLOOR</th>
                                        <th rowspan="2" class="align-middle text-nowrap">UNIT</th>
                                        <th rowspan="2" class="align-middle text-nowrap">UNIT PRICE</th>
                                        <th rowspan="2" class="align-middle text-nowrap">TOTAL PRICE</th>
                                        <th rowspan="2" class="align-middle text-nowrap">DISCOUNT (%)</th>
                                        <th rowspan="2" class="align-middle text-nowrap">DISCOUNT PRICE</th>
                                        <th rowspan="2" class="align-middle text-nowrap">DOWNPAYMENT (%)</th>
                                        <th rowspan="2" class="align-middle text-nowrap">DOWNPAYMENT PRICE</th>
                                        <th rowspan="2" class="align-middle text-nowrap">LEAD SOURCE</th>
                                        @for ($i = 1; $i <= $max_installments; $i++)
                                            <th colspan="4" class="align-middle text-nowrap border">{{ englishCounting($i) }}
                                                Installment</th>
                                        @endfor
                                        <th rowspan="2" class="align-middle text-nowrap">STATUS</th>
                                        <th rowspan="2" class="align-middle text-nowrap">APPROVED AT</th>
                                        <th rowspan="2" class="align-middle text-nowrap">Generated AT</th>
                                    </tr>

                                    <tr class="text-center">
                                        @for ($i = 1; $i <= $max_installments; $i++)
                                        <th class="align-middle text-nowrap border">Due Date</th>
                                        <th class="align-middle text-nowrap border">Paid Amount</th>
                                        <th class="align-middle text-nowrap border">Paid At</th>
                                        <th class="align-middle text-nowrap border">Remaining Amount</th>
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

        var flatpicker_approved_at = null, flatpicker_generated_at = null;
        $(document).ready(function() {

            flatpicker_generated_at = $("#filter_generated_at").flatpickr({
                mode: "range",
                altInput: !0,
                altFormat: "F j, Y",
                dateFormat: "Y-m-d",
            });

            flatpicker_approved_at = $("#filter_approved_at").flatpickr({
                mode: "range",
                altInput: !0,
                altFormat: "F j, Y",
                dateFormat: "Y-m-d",
            });


            var maxInstallments = parseInt("{{ $max_installments }}");

            var dataTableColumns = [{
                    name: 'unit_floor_name',
                    data: 'sales_plan.unit.floor',
                    className: 'text-center align-middle text-nowrap',
                    orderable: false,
                    render: function(data, type, row) {
                        return data ? data.name + " (" + data.short_label + ")" : '-';
                    }
                },
                {
                    name: 'unit_floor_unit_number',
                    data: 'sales_plan.unit.floor_unit_number',
                    className: 'text-center align-middle text-nowrap',
                    orderable: false,
                },
                {
                    name: 'sales_plan_unit_price',
                    data: 'sales_plan.unit_price',
                    className: 'text-center align-middle text-nowrap',
                    orderable: false,
                    render: function(data, type, row) {
                        return data ? numberFormat(data) : '-';
                    }
                },
                {
                    name: 'sales_plan_total_price',
                    data: 'sales_plan.total_price',
                    className: 'text-center align-middle text-nowrap',
                    orderable: false,
                    render: function(data, type, row) {
                        return data ? numberFormat(data) : '-';
                    }
                },
                {
                    name: 'sales_plan_discount_percentage',
                    data: 'sales_plan.discount_percentage',
                    className: 'text-center align-middle text-nowrap',
                    orderable: false,
                    render: function(data, type, row) {
                        return data ? numberFormat(data) : '-';
                    }
                },
                {
                    name: 'sales_plan_discount_total',
                    data: 'sales_plan.discount_total',
                    className: 'text-center align-middle text-nowrap',
                    orderable: false,
                    render: function(data, type, row) {
                        return data ? numberFormat(data) : '-';
                    }
                },
                {
                    name: 'sales_plan_down_payment_percentage',
                    data: 'sales_plan.down_payment_percentage',
                    className: 'text-center align-middle text-nowrap',
                    orderable: false,
                    render: function(data, type, row) {
                        return data ? numberFormat(data) : '-';
                    }
                },
                {
                    name: 'sales_plan_down_payment_total',
                    data: 'sales_plan.down_payment_total',
                    className: 'text-center align-middle text-nowrap',
                    orderable: false,
                    render: function(data, type, row) {
                        return data ? numberFormat(data) : '-';
                    }
                },
                {
                    name: 'sales_plan_lead_source_name',
                    data: 'sales_plan.lead_source.name',
                    className: 'text-center align-middle text-nowrap',
                    orderable: false,
                }
            ];

            for (let index = 1; index <= maxInstallments; index++) {

                dataTableColumns.push({
                    name: 'installment_' + index + '_date',
                    data: 'installments.installment_' + index + '_date',
                    className: 'text-center align-middle text-nowrap',
                    orderable: false,
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
                    orderable: false,
                    render: function(data, type, row) {
                        return data ? numberFormat(data) : '-';
                    }
                });

                dataTableColumns.push({
                    name: 'installment_' + index + '_updated_at',
                    data: 'installments.installment_' + index + '_updated_at',
                    className: 'text-center align-middle text-nowrap',
                    orderable: false,
                    render: function(data, type, row) {
                        if (data) {
                            return moment(data).format('DD/MM/YYYY');
                        } else {
                            return '-';
                        }
                    }
                });

                dataTableColumns.push({
                    name: 'installment_' + index + '_remaining_amount',
                    data: 'installments.installment_' + index + '_remaining_amount',
                    className: 'text-center align-middle text-nowrap',
                    orderable: false,
                    render: function(data, type, row) {
                        return data ? numberFormat(data) : '-';
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
                orderable: false,
                render: function(data, type, row) {
                    if (data) {
                        return '<span class="badge badge-pill ' + statusArray[data][1] + '">' +
                            statusArray[data][0] + '</span>';
                    } else {
                        return '-';
                    }
                }
            }, {
                name: 'sales_plan_approved_date',
                data: 'sales_plan.approved_date',
                className: 'text-center align-middle text-nowrap',
                orderable: false,
                render: function(data, type, row) {
                    if (data) {
                        return moment(data).format('DD/MM/YYYY');
                    } else {
                        return '-';
                    }
                }
            }, {
                name: 'sales_plan_created_at',
                data: 'sales_plan.created_at',
                className: 'text-center align-middle text-nowrap',
                orderable: false,
                render: function(data, type, row) {
                    if (data) {
                        return moment(data).format('DD/MM/YYYY');
                    } else {
                        return '-';
                    }
                }
            });

            var buttons = [{
                    extend: 'collection',
                    text: '<i class="bi bi-upload"></i> Export',
                    className: 'btn btn-relief-outline-secondary dropdown-toggle',
                    buttons: [{
                            extend: 'copy',
                            text: '<i class="bi bi-clipboard"></i> Copy',
                            className: 'dropdown-item',
                            // exportOptions: {
                            //     columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
                            // }
                        },
                        {
                            extend: 'csv',
                            text: '<i class="bi bi-file-earmark-spreadsheet"></i> CSV',
                            className: 'dropdown-item',
                            // exportOptions: {
                            //     columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
                            // }
                        },
                        {
                            extend: 'pdf',
                            text: '<i class="bi bi-filetype-pdf"></i> PDF',
                            className: 'dropdown-item',
                            // exportOptions: {
                            //     columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
                            // }
                        },
                        {
                            extend: 'excel',
                            text: '<i class="bi bi-file-earmark-spreadsheet"></i>Excel',
                            className: 'dropdown-item',
                            // exportOptions: {
                            //     columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
                            // }
                        },
                        {
                            extend: 'print',
                            text: '<i class="bi bi-printer"></i> Print',
                            className: 'dropdown-item',
                            // exportOptions: {
                            //     columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
                            // }
                        },
                    ]
                },
                {
                    name: 'reset',
                    text: '<i class="bi bi-arrow-counterclockwise"></i> Reset',
                    className: 'btn btn-relief-outline-danger',
                    action: function(e, dt, button, config) {
                        dt.search('');
                        dt.columns().search('');
                        dt.draw();
                    }
                },
                {
                    name: 'reload',
                    text: '<i class="bi bi-arrow-clockwise"></i> Reload',
                    className: 'btn btn-relief-outline-primary',
                    action: function(e, dt, button, config) {
                        dt.draw(false);
                    }
                },
            ];

            var salesPlanDataTable = $(".dt-complex-header").DataTable({
                processing: true,
                select: true,
                serverSide: true,
                scrollX: true,
                debug: true,
                dom: 'lrtipC',
                // dom: '<"card-header pt-0"<"head-label"><"dt-action-buttons text-end"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>> C<"clear">',
                ajax: {
                    url: '{{ route('sites.accounts.recovery.salesPlan', ['site_id' => ':site_id']) }}'
                        .replace(':site_id', "{{ encryptParams($site_id) }}"),

                },
                columns: dataTableColumns,
                buttons: buttons,
                displayLength: 20,
                lengthMenu: [20, 25, 50, 75, 100],
            });

            $('#dt_adv_search1').on('submit', function(e) {
                e.preventDefault();
                hideBlockUI();
                showBlockUI('#table-card');
                let filter_date_from = '',
                    filter_date_to = '';

                let filter_floors = $('#filter_floors').val();
                let filter_unit = $('#filter_unit').val();
                let filter_customer = $('#filter_customer').val();
                let filter_dealer = $('#filter_dealer').val();
                let filter_sale_source = $('#filter_sale_source').val();
                let filter_type = $('#filter_type').val();
                let filter_approved_at = $('#filter_approved_at').val();
                let filter_generated_at = $('#filter_generated_at').val();

                let data = '?';
                if (filter_floors) {
                    data += '&filter_floors=' + filter_floors;
                }
                if (filter_unit) {
                    data += '&filter_unit=' + filter_unit;
                }
                if (filter_customer) {
                    data += '&filter_customer=' + filter_customer;
                }
                if (filter_dealer) {
                    data += '&filter_dealer=' + filter_dealer;
                }
                if (filter_sale_source) {
                    data += '&filter_sale_source=' + filter_sale_source;
                }
                if (filter_type) {
                    data += '&filter_type=' + filter_type;
                }
                if (filter_generated_at) {
                    let generated_at_date_range = filter_generated_at.split(' ');

                    if (generated_at_date_range[0]) {
                        filter_date_from = generated_at_date_range[0];
                        filter_date_to = generated_at_date_range[0];
                    }

                    if (generated_at_date_range[2]) {
                        filter_date_to = generated_at_date_range[2];
                    }

                    data += '&filter_generated_from=' + filter_date_from + '&filter_generated_to=' +
                        filter_date_to;
                }
                if (filter_approved_at) {
                    var approved_date_range = filter_approved_at.split(' ');

                    if (approved_date_range[0]) {
                        filter_date_from = approved_date_range[0];
                        filter_date_to = approved_date_range[0];
                    }

                    if (approved_date_range[2]) {
                        filter_date_to = approved_date_range[2];
                    }

                    data += '&filter_approved_from=' + filter_date_from + '&filter_approved_to=' +
                        filter_date_to;
                }

                salesPlanDataTable.ajax.url(data).load();

                hideBlockUI('#table-card');
            });
        });

        function resetFilter() {
            $("#filter_customer").select2("val", "0");
            $("#filter_dealer").select2("val", "0");
            $("#filter_sale_source").select2("val", "0");
            $("#filter_type").select2("val", "0");
            flatpicker_generated_at.clear();
            flatpicker_approved_at.clear();

            $('#dt_adv_search1').trigger('submit');
        }
    </script>
@endsection

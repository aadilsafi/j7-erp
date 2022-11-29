@extends('app.layout.layout')




@section('page-title', 'Aging Report')

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
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/pages/dashboard-ecommerce.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/plugins/charts/chart-apex.min.css">
@endsection

@section('custom-css')
    <style>
        .dataTable .selected {
            background-color: #E3E1FC !important;
        }

        label {
            /* color: Lime; */
        }

        .canvasjs-chart-credit {
            display: none !important;
        }

        /* .canvasjs-chart-canvas {
                                                                                display: none !important;
                                                                                width: 438px !important;
                                                                                height: 300px !important;
                                                                            } */
    </style>
@endsection

@section('breadcrumbs')
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-start mb-0"> Aging Report</h2>
            </div>
        </div>
    </div>
@endsection

@section('page-vendor')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/vendors/css/charts/apexcharts.css">
@endsection

@section('page-css')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/pages/dashboard-ecommerce.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/plugins/charts/chart-apex.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cdbootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cdbootstrap/css/cdb.min.css" />
@endsection

@section('custom-css')
@endsection


@section('content')
    <p class="mb-2">
    </p>

    <div class="row">
        <div class="row">
            <div class="col-lg-9 col-md-9 col-sm-12 position-relative">
                <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                    <div class="card-body">
                        <div class="row mb-1 g-1">
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 position-relative">
                                <label class="form-label fs-5" for="type_name">Search For User Name</label>
                                <select class="select2-size-lg form-select col-filter" id="type_name" name="type_name">
                                    <option value="" selected>Select User Name</option>
                                    @php
                                        $installment_large_number = [];
                                    @endphp
                                    @foreach ($salesPlans as $salesPlan)
                                        @php
                                            
                                            array_push($installment_large_number, $salesPlan->installments->pluck('details')->count());
                                        @endphp
                                        <option value="{{ $salesPlan->id }}">{{ $salesPlan->stakeholder->full_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 position-relative">
                                <label class="form-label fs-5" for="installment_value">Select Installment</label>
                                <select class="select2-size-lg form-select col-filter" id="installment_value"
                                    name="installment_value">
                                    <option value="0" selected>Select Installment</option>
                                    @for ($i = 1; $i <= collect($installment_large_number)->max(); $i++)
                                        <option value="{{ englishCounting($i) }} Installment">{{ englishCounting($i) }}
                                            Installment</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 position-relative">
                                <label class="form-label fs-5" for="unit_value">Select Unit</label>
                                <select class="select2-size-lg form-select col-filter" id="unit_value" name="unit_value">
                                    <option value="0" selected>Select Unit</option>
                                    @foreach ($salesPlans as $salesPlan)
                                        <option value="{{ $salesPlan->unit->id }}">{{ $salesPlan->unit->name }} -
                                            {{ $salesPlan->unit->floor_unit_number }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 position-relative">
                                <label class="form-label" style="font-size: 15px" for="to_date">Select Date
                                    Range</label>
                                <input type="text" id="to_date" name="to_date"
                                    class="form-control flatpickr-range flatpickr-input active filter_date_ranger"
                                    placeholder="YYYY-MM-DD to YYYY-MM-DD" readonly="readonly">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-3 col-sm-12 position-relative">
                <div class="sticky-md-top top-lg-100px top-md-100px top-sm-0px" style="z-index: auto;">
                    <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                        <div class="card-body">
                            <div class="row g-1">
                                <div class="col-md-12">
                                    <button type="button" value="asd" name="apply_filter" id="apply_filter"
                                        class="btn btn-relief-outline-success w-100 waves-effect waves-float waves-light buttonToBlockUI me-1">
                                        <i data-feather='save'></i>
                                        Apply Filter
                                    </button>
                                </div>
                                <div class="col-md-12">
                                    <button onclick="resetFilter()"
                                        class="btn btn-relief-outline-danger w-100 waves-effect waves-float waves-light"
                                        type="button">
                                        <i data-feather='x'></i>Reset</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="container">
                <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                    <div class="card-body">
                        <div class="container col-md-12" id="main">
                            <div class="row" id="main_heading">
                                <div class="col-md-12">
                                    <h3 class="text-center">
                                        Inventory Aging
                                    </h3>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-sm-12" id="full_bar_graph">
                                    <div class="row mt-3" id="sub_manu">
                                        <div class="col-md-4 sm-md-12 responsive">
                                            <p>
                                                <b> Due Amt</b>
                                            </p>
                                            <h6 id="amount_due">
                                                726,001,501
                                            </h6>
                                        </div>
                                        <div class="col-md-4 sm-md-12">
                                            <p>
                                                <b> Received </b>
                                            </p>
                                            <h6 id="received_amout_top">
                                                1,458,832
                                            </h6>
                                        </div>
                                        <div class="col-md-4 sm-md-12">
                                            <p>
                                                <b> Net Receivable </b>
                                            </p>
                                            <h6 id="not_received_amout_top">
                                                724,542,675
                                            </h6>
                                        </div>
                                    </div>
                                    <div class="card chart-container" style="float:left;width:100%;overflow-y: auto;">

                                        {{-- <canvas id="chart"></canvas> --}}
                                        <div id="chartContainer" style="height: 300px; width: 100%;"></div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12" id="pipe_chart_bar">
                                    <div id="pipe_chart_filter_data" class="row">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="container">
                                                    <div class="row my-2">
                                                        <div class="col">
                                                            <h6 class="text-center"><b> Filter Data Show </b></h6>
                                                        </div>
                                                    </div>
                                                    <div class="row py-2">
                                                        <input type="hidden" name="amount" id="amount">
                                                        <input type="hidden" name="paid_amount" id="paid_amount">
                                                        <input type="hidden" name="due_amount" id="due_amount">
                                                        <input type="hidden" name="remaining_amount"
                                                            id="remaining_amount">
                                                        <div class="col-md-12 py-1">
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <canvas id="chDonut3"></canvas>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 py-1">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>


                        </div>

                    </div>


                </div>
            </div>
        </div>

        <div class="row">
            <div class="container">
                <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                    <div class="card-body">
                        <div class="row table-responsive">
                            <table id="example" class="table table-striped dt-complex-header table" style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="text-nowrap" scope="col">#</th>
                                        <th class="text-nowrap" scope="col">Due Date</th>
                                        <th class="text-nowrap" scope="col">Unit</th>
                                        <th class="text-nowrap" scope="col">Customer</th>
                                        <th class="text-nowrap" scope="col">Instalment</th>
                                        <th class="text-nowrap" scope="col">Amount</th>
                                        <th class="text-nowrap" scope="col">Remaining Amount</th>
                                        <th class="text-nowrap" scope="col">Received</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i = 1;
                                        $amount_installment = [];
                                    @endphp



                                    @foreach ($salesPlans as $salesPlan)
                                        @php
                                            $amount = $salesPlan->installments->pluck('amount')->sum();
                                            $amount_due = $salesPlan->installments
                                                ->where('date', now())
                                                ->pluck('amount')
                                                ->sum();
                                            $remaining_amount = $salesPlan->installments->pluck('remaining_amount')->sum();
                                            $paid_amount = $salesPlan->installments->pluck('paid_amount')->sum();
                                            array_push($amount_installment, ['amount' => $amount, 'remaining_amount' => $remaining_amount, 'paid_amount' => $paid_amount, 'amount_due' => $amount_due]);
                                        @endphp
                                        @foreach ($salesPlan->installments as $key => $installment)
                                            {{-- @dd($salesPlan->stakeholder->full_name) --}}
                                            <tr>
                                                <th scope="row">{{ $i }}</th>
                                                <td class="text-nowrap">
                                                    <span>{{ date_format(new DateTime($installment->date), 'h:i:s') }}</span><br>
                                                    <span
                                                        class="text-primary fw-bold">{{ date_format(new DateTime($installment->date), 'Y-m-d') }}</span>
                                                </td>
                                                <td class="text-nowrap">{{ $salesPlan->unit->name }} -
                                                    {{ $salesPlan->unit->floor_unit_number }}</td>
                                                <td class="text-nowrap">
                                                    {{ $salesPlan->stakeholder->full_name ?? '' }}
                                                </td>
                                                <td class="text-nowrap">{{ $key }}</td>
                                                <td class="text-nowrap">{{ number_format($installment->amount) }}</td>
                                                <td class="text-nowrap">
                                                    {{ number_format($installment->remaining_amount) }}
                                                </td>
                                                <td class="text-nowrap">{{ number_format($installment->paid_amount) }}
                                                </td>
                                            </tr>


                                            @php
                                                $i++;
                                            @endphp
                                        @endforeach
                                    @endforeach
                                    @php
                                        $amount_sum_array = [];
                                        $amount_sum = collect($amount_installment)
                                            ->pluck('amount')
                                            ->sum();
                                        $remaining_amount_sum = collect($amount_installment)
                                            ->pluck('remaining_amount')
                                            ->sum();
                                        $paid_amount = collect($amount_installment)
                                            ->pluck('paid_amount')
                                            ->sum();
                                        $amount_due = collect($amount_installment)
                                            ->pluck('amount_due')
                                            ->sum();
                                        echo '<input type="hidden" id="amount_sum" value="' . $amount_sum . '">';
                                        echo '<input type="hidden" id="remaining_amount_sum" value="' . $remaining_amount_sum . '">';
                                        echo '<input type="hidden" id="paid_amount" value="' . $paid_amount . '">';
                                        if ($amount_due > 0) {
                                            echo '<input type="hidden" id="amount_due" value="' . $amount_due . '">';
                                        } else {
                                            echo '<input type="hidden" id="amount_due" value="' . '0' . '">';
                                        }
                                    @endphp

                                </tbody>
                            </table>
                        </div>
                    </div>
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
    <script src="{{ asset('app-assets') }}/vendors/js/charts/chart.min.js"></script>
    <script src="{{ asset('app-assets') }}/js/core/app-menu.min.js"></script>
    <script src="{{ asset('app-assets') }}/js/core/app.min.js"></script>
    <script src="{{ asset('app-assets') }}/js/scripts/customizer.min.js"></script>
    <script src="{{ asset('app-assets') }}/js/scripts/charts/chart-chartjs.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/cdbootstrap/js/cdb.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/cdbootstrap/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/9d1d9a82d2.js" crossorigin="anonymous"></script>
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
@endsection

@section('page-js')

@endsection

@section('custom-js')
    <script>
        $(document).ready(function() {
            var table = $('#example').DataTable({
                responsive: true
            });
        });


        var flatpicker_to_date = null;
        $(document).ready(function() {

            flatpicker_to_date = $("#to_date").flatpickr({
                mode: "range",
                altInput: !0,
                altFormat: "F j, Y",
                dateFormat: "Y-m-d",
            });

            $('#apply_filter').on('click', function(e) {
                e.preventDefault();
                hideBlockUI();
                let filter_date_from = '',
                    filter_date_to = '';
                let to_date = $('#to_date').val();
                let type_name = $('#type_name').val();
                let installment_value = $('#installment_value').val();
                let unit_value = $('#unit_value').val();
                let data_data = '';
                if (to_date) {
                    let generated_at_date_range = to_date.split(' ');

                    if (generated_at_date_range[0]) {
                        filter_date_from = generated_at_date_range[0];
                        filter_date_to = generated_at_date_range[0];
                    }

                    if (generated_at_date_range[2]) {
                        filter_date_to = generated_at_date_range[2];
                    }

                    data_data += '&filter_generated_from=' + filter_date_from + '&filter_generated_to=' +
                        filter_date_to;
                }
                let url =
                    "{{ route('sites.accounts.recovery.ajax-filter-inventory-aging', ['site_id' => encryptParams($site->id)]) }}";
                var _token = '{{ csrf_token() }}';
                $.ajax({
                    url: url,
                    type: 'post',
                    dataType: 'json',
                    data: {
                        'date_filter': data_data,
                        'to_date': to_date,
                        'stakeholder_id': type_name,
                        'installment_id': installment_value,
                        'salesPlan_unit_id': unit_value,
                        '_token': _token,
                    },
                    success: function(data) {
                        if (data.status == true) {
                            $('#pipe_chart_bar').removeClass('d-none').addClass('d-block');
                            $('#full_bar_graph').removeClass('col-md-12').addClass('col-md-6');
                            var data_pipe_chart = [];
                            data_pipe_chart.push(data.data[0].amount, data.data[0].paid_amount,
                                data.data[0].due_amount, data.data[0].remaining_amount);
                            $('#amount').val(data.data[0].amount);
                            $('#paid_amount').val(data.data[0].paid_amount);
                            $('#due_amount').val(data.data[0].due_amount);
                            $('#remaining_amount').val(data.data[0].remaining_amount);
                            /* chart.js chart examples */

                            // chart colors
                            var colors = ['#007bff', '#28a745', '#333333', '#c3e6cb', '#dc3545',
                                '#6c757d'
                            ];

                            /* large pie/donut chart */
                            var chPie = document.getElementById("chPie");
                            if (chPie) {
                                new Chart(chPie, {
                                    type: 'pie',
                                    data: {
                                        labels: ['Desktop', 'Phone', 'Tablet',
                                            'Unknown'
                                        ],
                                        datasets: [{
                                            backgroundColor: [colors[1], colors[
                                                0], colors[2], colors[
                                                5]],
                                            borderWidth: 0,
                                            data: [50, 40, 15, 5]
                                        }]
                                    },
                                    plugins: [{
                                        beforeDraw: function(chart) {
                                            var width = chart.chart.width,
                                                height = chart.chart.height,
                                                ctx = chart.chart.ctx;
                                            ctx.restore();
                                            var fontSize = (height / 70)
                                                .toFixed(2);
                                            ctx.font = fontSize +
                                                "em sans-serif";
                                            ctx.textBaseline = "middle";
                                            var text = chart.config.data
                                                .datasets[0].data[0] + "%",
                                                textX = Math.round((width -
                                                        ctx.measureText(
                                                            text).width) /
                                                    2),
                                                textY = height / 2;
                                            ctx.fillText(text, textX,
                                                textY);
                                            ctx.save();
                                        }
                                    }],
                                    options: {
                                        layout: {
                                            padding: 0
                                        },
                                        legend: {
                                            display: false
                                        },
                                        cutoutPercentage: 80
                                    }
                                });
                            }


                            /* 3 donut charts */
                            var donutOptions = {
                                cutoutPercentage: 85,
                                legend: {
                                    position: 'bottom',
                                    padding: 5,
                                    labels: {
                                        pointStyle: 'circle',
                                        usePointStyle: true
                                    }
                                }
                            };

                            // donut 1
                            var chDonutData1 = {
                                labels: ['Bootstrap', 'Popper', 'Other'],
                                datasets: [{
                                    backgroundColor: colors.slice(0, 3),
                                    borderWidth: 0,
                                    data: [74, 11, 40]
                                }]
                            };
                            console.log(amount_pipe_chart,
                                paid_amount_pipe_chart,
                                due_amount_pipe_chart, 'pipe chart value')
                            // donut 3
                            var chDonutData3 = {

                                labels: ["Due Amount", "Amount", "Paid Amount",
                                    "Remaining Amount"
                                ],
                                datasets: [{
                                    backgroundColor: colors.slice(0, 3),
                                    borderWidth: 0,
                                    data: [data.data[0].due_amount, data.data[0]
                                        .amount, data.data[0].paid_amount, data
                                        .data[0].remaining_amount
                                    ]
                                }]
                            };
                            var chDonut3 = document.getElementById("chDonut3");
                            if (chDonut3) {
                                new Chart(chDonut3, {
                                    type: 'pie',
                                    data: chDonutData3,
                                    options: donutOptions
                                });
                            }

                            /* 3 line charts */
                            var lineOptions = {
                                legend: {
                                    display: false
                                },
                                tooltips: {
                                    interest: false,
                                    bodyFontSize: 11,
                                    titleFontSize: 11
                                },
                                scales: {
                                    xAxes: [{
                                        ticks: {
                                            display: false
                                        },
                                        gridLines: {
                                            display: false,
                                            drawBorder: false
                                        }
                                    }],
                                    yAxes: [{
                                        display: false
                                    }]
                                },
                                layout: {
                                    padding: {
                                        left: 6,
                                        right: 6,
                                        top: 4,
                                        bottom: 6
                                    }
                                }
                            };

                            var chLine1 = document.getElementById("chLine1");
                            if (chLine1) {
                                new Chart(chLine1, {
                                    type: 'line',
                                    data: {
                                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
                                        datasets: [{
                                            backgroundColor: '#ffffff',
                                            borderColor: '#ffffff',
                                            data: [10, 11, 4, 11, 4],
                                            fill: false
                                        }]
                                    },
                                    options: lineOptions
                                });
                            }
                            var chLine2 = document.getElementById("chLine2");
                            if (chLine2) {
                                new Chart(chLine2, {
                                    type: 'line',
                                    data: {
                                        labels: ['A', 'B', 'C', 'D', 'E'],
                                        datasets: [{
                                            backgroundColor: '#ffffff',
                                            borderColor: '#ffffff',
                                            data: [4, 5, 7, 13, 12],
                                            fill: false
                                        }]
                                    },
                                    options: lineOptions
                                });
                            }

                            var chLine3 = document.getElementById("chLine3");
                            if (chLine3) {
                                new Chart(chLine3, {
                                    type: 'line',
                                    data: {
                                        labels: ['Pos', 'Neg', 'Nue', 'Other',
                                            'Unknown'
                                        ],
                                        datasets: [{
                                            backgroundColor: '#ffffff',
                                            borderColor: '#ffffff',
                                            data: [13, 15, 10, 9, 14],
                                            fill: false
                                        }]
                                    },
                                    options: lineOptions
                                });
                            }


















                        } else {
                            console.log(data.data);
                        }

                    },
                    error: function(error) {
                        console.log(error);
                    }
                });

            });
        });

        function resetFilter() {

            $('#form_date').val('');
            $('#to_date').val('');
            $('#pipe_chart_filter_data').addClass('d-none');
            $("#type_name").select2("val", "0");
            $("#unit_value").select2("val", "0");
            $("#installment_value").select2("val", "0");
            flatpicker_to_date.clear();

            // $('#apply_filter').trigger('click');

        }
    </script>
    <script>
        function numberWithCommas(num) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        }

        var amount = $('#amount_sum').val();
        var amount_pipe_chart = $('#amount').val();
        var paid_amount_pipe_chart = $('#paid_amount').val();
        var due_amount_pipe_chart = $('#due_amount').val();
        var remaining_amount_sum = $('#remaining_amount_sum').val();
        var paid_amount = $('#paid_amount').val();
        var amount_due = $('#amount_due').val();
        $('#received_amout_top').html('<h6>' + numberWithCommas(paid_amount) + '</h6>');
        $('#not_received_amout_top').html('<h6>' + numberWithCommas(remaining_amount_sum) + '</h6>');
        if (amount_due > 0) {

            $('#amount_due').html('<h6>' + numberWithCommas(amount_due) + '</h6>');
        } else {

            $('#amount_due').html('<h6>' + 0 + '</h6>');
        }
        console.log(amount_due > 0);
        const ctx = document.getElementById("chart").getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                //working chart
                labels: ["Amount", "Remaining Amount", "Paid Amount"],
                datasets: [{
                    label: 'Payment',
                    backgroundColor: 'rgba(161, 198, 247, 1)',
                    borderColor: 'rgb(47, 128, 237)',
                    data: [amount, remaining_amount_sum, paid_amount],
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                        }
                    }]
                }
            },
        });












        /* chart.js chart examples */

        // chart colors
        var colors = ['#007bff', '#28a745', '#333333', '#c3e6cb', '#dc3545', '#6c757d'];

        /* large pie/donut chart */
        var chPie = document.getElementById("chPie");
        if (chPie) {
            new Chart(chPie, {
                type: 'pie',
                data: {
                    labels: ['Desktop', 'Phone', 'Tablet', 'Unknown'],
                    datasets: [{
                        backgroundColor: [colors[1], colors[0], colors[2], colors[5]],
                        borderWidth: 0,
                        data: [50, 40, 15, 5]
                    }]
                },
                plugins: [{
                    beforeDraw: function(chart) {
                        var width = chart.chart.width,
                            height = chart.chart.height,
                            ctx = chart.chart.ctx;
                        ctx.restore();
                        var fontSize = (height / 70).toFixed(2);
                        ctx.font = fontSize + "em sans-serif";
                        ctx.textBaseline = "middle";
                        var text = chart.config.data.datasets[0].data[0] + "%",
                            textX = Math.round((width - ctx.measureText(text).width) / 2),
                            textY = height / 2;
                        ctx.fillText(text, textX, textY);
                        ctx.save();
                    }
                }],
                options: {
                    layout: {
                        padding: 0
                    },
                    legend: {
                        display: false
                    },
                    cutoutPercentage: 80
                }
            });
        }


        /* 3 donut charts */
        var donutOptions = {
            cutoutPercentage: 85,
            legend: {
                position: 'bottom',
                padding: 5,
                labels: {
                    pointStyle: 'circle',
                    usePointStyle: true
                }
            }
        };

        // donut 1
        var chDonutData1 = {
            labels: ['Bootstrap', 'Popper', 'Other'],
            datasets: [{
                backgroundColor: colors.slice(0, 3),
                borderWidth: 0,
                data: [74, 11, 40]
            }]
        };
        console.log(amount_pipe_chart,
            paid_amount_pipe_chart,
            due_amount_pipe_chart, 'pipe chart value')
        // donut 3
        console.log(data_pipe_chart);
        var chDonutData3 = {

            labels: ["Due Amount", "Amount", "Paid Amount", "Remaining Amount"],
            datasets: [{
                backgroundColor: colors.slice(0, 3),
                borderWidth: 0,
                data: data_pipe_chart
            }]
        };
        var chDonut3 = document.getElementById("chDonut3");
        if (chDonut3) {
            new Chart(chDonut3, {
                type: 'pie',
                data: chDonutData3,
                options: donutOptions
            });
        }

        /* 3 line charts */
        var lineOptions = {
            legend: {
                display: false
            },
            tooltips: {
                interest: false,
                bodyFontSize: 11,
                titleFontSize: 11
            },
            scales: {
                xAxes: [{
                    ticks: {
                        display: false
                    },
                    gridLines: {
                        display: false,
                        drawBorder: false
                    }
                }],
                yAxes: [{
                    display: false
                }]
            },
            layout: {
                padding: {
                    left: 6,
                    right: 6,
                    top: 4,
                    bottom: 6
                }
            }
        };

        var chLine1 = document.getElementById("chLine1");
        if (chLine1) {
            new Chart(chLine1, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
                    datasets: [{
                        backgroundColor: '#ffffff',
                        borderColor: '#ffffff',
                        data: [10, 11, 4, 11, 4],
                        fill: false
                    }]
                },
                options: lineOptions
            });
        }
        var chLine2 = document.getElementById("chLine2");
        if (chLine2) {
            new Chart(chLine2, {
                type: 'line',
                data: {
                    labels: ['A', 'B', 'C', 'D', 'E'],
                    datasets: [{
                        backgroundColor: '#ffffff',
                        borderColor: '#ffffff',
                        data: [4, 5, 7, 13, 12],
                        fill: false
                    }]
                },
                options: lineOptions
            });
        }

        var chLine3 = document.getElementById("chLine3");
        if (chLine3) {
            new Chart(chLine3, {
                type: 'line',
                data: {
                    labels: ['Pos', 'Neg', 'Nue', 'Other', 'Unknown'],
                    datasets: [{
                        backgroundColor: '#ffffff',
                        borderColor: '#ffffff',
                        data: [13, 15, 10, 9, 14],
                        fill: false
                    }]
                },
                options: lineOptions
            });
        }
    </script>


    <script>
        window.onload = function() {


            var chart = new CanvasJS.Chart("chartContainer", {
                animationEnabled: true,
                title: {
                    // text: "Email Categories",
                    horizontalAlign: "left"
                },
                data: [{
                    type: "doughnut",
                    startAngle: 60,
                    //innerRadius: 60,
                    indexLabelFontSize: 17,
                    // indexLabel: "{label} - #percent%",
                    // toolTipContent: "<b>{label}:</b> {y} (#percent%)",
                    dataPoints: [{
                            y: amount,
                            label: "Amount"
                        },
                        {
                            y: remaining_amount_sum,
                            label: "Remaining Amount"
                        },
                        {
                            y: paid_amount,
                            label: "Paid Amount"
                        },
                    ]
                }]
            });
            chart.render();


        }



        $(document).ready(function() {
            $('.canvasjs-chart-credit').find('.canvasjs-chart-credit').addClass('d-none');
        });
    </script>
@endsection

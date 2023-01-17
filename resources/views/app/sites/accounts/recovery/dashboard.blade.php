@extends('app.layout.layout')

@section('seo-breadcrumb')
     {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.accounts.recovery.dashboard') }}
@endsection

@section('page-title', 'Dashboard')

@section('page-vendor')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/vendors/css/charts/apexcharts.css">
@endsection

@section('page-css')
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/pages/dashboard-ecommerce.min.css"> --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/plugins/charts/chart-apex.min.css">
@endsection

@section('custom-css')
    <style>
        .font-large-1 {
            font-size: 1rem !important;
        }

        .font-large-2 {
            font-size: 2rem !important;
        }

        .inline-text-inline {
            white-space: nowrap;

        }

        .centered {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        /* #apexchartsj0rxcjpl,
                                                                                                                                                                                                                                                                                                                                    #SvgjsSvg1119,
                                                                                                                                                                                                                                                                                                                                    .apexcharts-svg {
                                                                                                                                                                                                                                                                                                                                        display: none;
                                                                                                                                                                                                                                                                                                                                    } */
    </style>
@endsection

@section('breadcrumbs')
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-start mb-0">Dashboard </h2>
                <div class="breadcrumb-wrapper">
                     {{ Breadcrumbs::render('sites.accounts.recovery.dashboard') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <!-- Dashboard Analytics Start -->
    <p class="mb-2">
    </p>

    <div class="card">
        <div class="card-body">
            <div class="p-2 m-3">
                <div class="col align-items-center">
                    <div class="w-100 d-lg-flex align-items-center justify-content-center px-5">
                        <img class="img-fluid" src="{{ asset('app-assets') }}/images/coming_soon.png" alt="Login V2" />
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('vendor-js')
    <script src="{{ asset('app-assets') }}/vendors/js/charts/apexcharts.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/extensions/toastr.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/extensions/moment.min.js"></script>
@endsection

@section('page-js')
    <script src="{{ asset('app-assets') }}/js/scripts/pages/dashboard-analytics.min.js"></script>
    <script src="{{ asset('app-assets') }}/js/scripts/pages/app-invoice-list.min.js"></script>
    <script src="{{ asset('app-assets') }}/js/scripts/pages/dashboard-ecommerce.min.js"></script>
@endsection

@section('custom-js')
    <script type="text/javascript">
        $(document).ready(function() {


            $("#months1,#months3,#months6,#months12").on('click', function(e) {
                // alert("Field " + e.target.id + " changed");
                // alert('q');
                let months_id = e.target.id;
                e.preventDefault();
                let months1 = $('#months1').val();
                let months3 = $('#months3').val();
                let months6 = $('#months6').val();
                let months12 = $('#months12').val();
                let url =
                    "{{ route('ajax-get-filtered-data-dasboard') }}";
                var _token = '{{ csrf_token() }}';
                $.ajax({
                    url: url,
                    type: 'post',
                    dataType: 'json',
                    data: {
                        'months1': months1,
                        'months3': months3,
                        'months6': months6,
                        'months12': months12,
                        'months_id': months_id,
                        '_token': _token,
                    },
                    success: function(data) {
                        if (data.status == true) {
                            function numberWithCommas(num) {
                                return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                            }
                            console.log(data.data.new_percentage, 'percentage');
                            // reload_cart(data.data.amount, data.data.paid_amount, data.data
                            //     .remaining_amount);

                            $('#amount').val(data.data.amount);
                            $('#amount').html(
                                '<span class="font-large-1 fw-bold" id="amount">' +
                                numberWithCommas(data
                                    .data.amount) + '</span>');
                            $('#paid_amount').val(data.data.paid_amount);
                            $('#paid_amount').html(
                                '<span class="font-large-1 fw-bold" id="amount">' +
                                numberWithCommas(data
                                    .data.paid_amount) + '</span>');
                            // $('#due_amount').val(data.data.due_amount);
                            $('#remaining_amount').val(data.data.remaining_amount);
                            $('#remaining_amount').html(
                                '<span class="font-large-1 fw-bold" id="amount">' +
                                numberWithCommas(data
                                    .data.paid_amount) + '</span>');
                            $('#installment_paid').html(
                                '<h1 class="font-large-2 fw-bolder mt-2 mb-0" id="installment_paid">' +
                                numberWithCommas(data
                                    .data.installment_paid) + '</h1>');
                            ring_chart(data.data.new_percentage);
                            // $("#support-trackers-chart").first().css("display",
                            //     "none");
                        } else {
                            console.log(data.data);
                        }

                    },
                    error: function(error) {
                        console.log(error);
                    }
                });

            });





            // side chart
            $("#side_months1,#side_months3,#side_months6,#side_months12").on('click', function(e) {

                let months_id = e.target.id;
                e.preventDefault();
                let url =
                    "{{ route('ajax-get-dasboard-side-chart') }}";
                var _token = '{{ csrf_token() }}';
                $.ajax({
                    url: url,
                    type: 'post',
                    dataType: 'json',
                    data: {
                        'months_id': months_id,
                        '_token': _token,
                    },
                    success: function(data) {
                        if (data.status == true) {
                            console.log(data.data, 'value check')

                            function numberWithCommas(num) {
                                return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                            }
                            if (data.data[0].downpaidment) {



                                $('#downpaidment').html(
                                    `<div class="col-6 mb-2" id="downpaidment">
                                        <p class="mb-50  inline-text-inline">DownPayment:</p>
                                        ` +
                                    '<p class="mb-50 inline-text-inline">Amount: ' +
                                    numberWithCommas(data
                                        .data[0].downpaidment
                                        .amount) + '</p>' +
                                    `<p class="mb-50  inline-text-inline">Paid Amount: ${numberWithCommas(data.data[0].downpaidment.paid_amount)}</p>
                                <p class="mb-50 inline-text-inline">Remaining Amount: ${numberWithCommas(data.data[0].downpaidment.remaining_amount)}</p>
                                <p class="mb-50 inline-text-inline">Receivable Percentage: ${Math.round(data.data[0].downpaidment.revicable_amount)}%</p>
                                <div class="progress progress-bar-primary" style="height: 6px">
                                    <div class="progress-bar" role="progressbar" aria-valuenow="50" aria-valuemin="50"
                                        aria-valuemax="100" style="width:${data.data[0].downpaidment.revicable_amount}%"></div>
                                </div>
                            </div>`);
                            }
                            if (data.data[0].installment) {



                                $('#installment').html(
                                    `<div class="col-6 mb-2" id="installment">
                                        <p class="mb-50  inline-text-inline">Installment:</p>
                                        ` +
                                    '<p class="mb-50 inline-text-inline">Amount: ' +
                                    numberWithCommas(data
                                        .data[0].installment
                                        .amount) + '</p>' +
                                    `<p class="mb-50  inline-text-inline">Paid Amount: ${numberWithCommas(data.data[0].installment.paid_amount)}</p>
                                <p class="mb-50 inline-text-inline">Remaining Amount: ${numberWithCommas(data.data[0].installment.remaining_amount)}</p>
                                <p class="mb-50 inline-text-inline">Receivable Percentage: ${Math.round(data.data[0].installment.revicable_amount)}%</p>
                                <div class="progress progress-bar-warning" style="height: 6px">
                                    <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="60"
                                        aria-valuemax="100" style="width: ${data.data[0].installment.revicable_amount}%"></div>
                                </div>
                            </div>`);
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




        function ring_chart(amount) {

            "use strict";
            var e,
                o,
                t,
                r,
                a,
                s = "#ebf0f7",
                i = "#5e5873",
                n = "#ebe9f1",
                d = document.querySelector("#gained-chart"),
                l = document.querySelector("#order-chart"),
                h = document.querySelector("#avg-sessions-chart"),
                p = document.querySelector("#support-trackers-chart"),
                c = document.querySelector("#sales-visit-chart"),
                w = "rtl" === $("html").attr("data-textdirection");
            setTimeout(function() {
                    // toastr.success(
                    //     "You have successfully logged in to Vuexy. Now you can start to explore!",
                    //     "👋 Welcome John Doe!", {
                    //         closeButton: !0,
                    //         tapToDismiss: !1,
                    //         rtl: w
                    //     }
                    // );
                }, 2e3),
                (e = {
                    chart: {
                        height: 100,
                        type: "area",
                        toolbar: {
                            show: !1
                        },
                        sparkline: {
                            enabled: !0
                        },
                        grid: {
                            show: !1,
                            padding: {
                                left: 0,
                                right: 0
                            }
                        },
                    },
                    colors: [window.colors.solid.primary],
                    dataLabels: {
                        enabled: !1
                    },
                    stroke: {
                        curve: "smooth",
                        width: 2.5
                    },
                    fill: {
                        type: "gradient",
                        gradient: {
                            shadeIntensity: 0.9,
                            opacityFrom: 0.7,
                            opacityTo: 0.5,
                            stops: [0, 80, 100],
                        },
                    },
                    series: [{
                        name: "Subscribers",
                        data: [28, 40, 36, 52, 38, 60, 55]
                    }, ],
                    xaxis: {
                        labels: {
                            show: !1
                        },
                        axisBorder: {
                            show: !1
                        }
                    },
                    yaxis: [{
                        y: 0,
                        offsetX: 0,
                        offsetY: 0,
                        padding: {
                            left: 0,
                            right: 0
                        },
                    }, ],
                    tooltip: {
                        x: {
                            show: !1
                        }
                    },
                }),
                new ApexCharts(d, e).render(),
                (o = {
                    chart: {
                        height: 100,
                        type: "area",
                        toolbar: {
                            show: !1
                        },
                        sparkline: {
                            enabled: !0
                        },
                        grid: {
                            show: !1,
                            padding: {
                                left: 0,
                                right: 0
                            }
                        },
                    },
                    colors: [window.colors.solid.warning],
                    dataLabels: {
                        enabled: !1
                    },
                    stroke: {
                        curve: "smooth",
                        width: 2.5
                    },
                    fill: {
                        type: "gradient",
                        gradient: {
                            shadeIntensity: 0.9,
                            opacityFrom: 0.7,
                            opacityTo: 0.5,
                            stops: [0, 80, 100],
                        },
                    },
                    series: [{
                        name: "Orders",
                        data: [10, 15, 8, 15, 7, 12, 8]
                    }],
                    xaxis: {
                        labels: {
                            show: !1
                        },
                        axisBorder: {
                            show: !1
                        }
                    },
                    yaxis: [{
                        y: 0,
                        offsetX: 0,
                        offsetY: 0,
                        padding: {
                            left: 0,
                            right: 0
                        },
                    }, ],
                    tooltip: {
                        x: {
                            show: !1
                        }
                    },
                }),
                new ApexCharts(l, o).render(),
                (t = {
                    chart: {
                        type: "bar",
                        height: 200,
                        sparkline: {
                            enabled: !0
                        },
                        toolbar: {
                            show: !1
                        },
                    },
                    states: {
                        hover: {
                            filter: "none"
                        }
                    },
                    colors: [s, s, window.colors.solid.primary, s, s, s],
                    series: [{
                        name: "Sessions",
                        data: [75, 125, 225, 175, 125, 75, 25]
                    }, ],
                    grid: {
                        show: !1,
                        padding: {
                            left: 0,
                            right: 0
                        }
                    },
                    plotOptions: {
                        bar: {
                            columnWidth: "45%",
                            distributed: !0,
                            endingShape: "rounded",
                        },
                    },
                    tooltip: {
                        x: {
                            show: !1
                        }
                    },
                    xaxis: {
                        type: "numeric"
                    },
                }),
                new ApexCharts(h, t).render(),
                (r = {
                    chart: {
                        height: 270,
                        type: "radialBar"
                    },
                    plotOptions: {
                        radialBar: {
                            size: 150,
                            offsetY: 20,
                            startAngle: -150,
                            endAngle: 150,
                            hollow: {
                                size: "65%"
                            },
                            track: {
                                background: "#fff",
                                strokeWidth: "100%"
                            },
                            dataLabels: {
                                name: {
                                    offsetY: -5,
                                    color: i,
                                    fontSize: "1rem"
                                },
                                value: {
                                    offsetY: 15,
                                    color: i,
                                    fontSize: "1.714rem"
                                },
                            },
                        },
                    },
                    colors: [window.colors.solid.danger],
                    fill: {
                        type: "gradient",
                        gradient: {
                            shade: "dark",
                            type: "horizontal",
                            shadeIntensity: 0.5,
                            gradientToColors: [window.colors.solid.primary],
                            inverseColors: !0,
                            opacityFrom: 1,
                            opacityTo: 1,
                            stops: [0, 100],
                        },
                    },
                    stroke: {
                        dashArray: 8
                    },
                    series: [amount],
                    labels: ["Completed"],
                }),
                new ApexCharts(p, r).render(),
                (a = {
                    chart: {
                        height: 300,
                        type: "radar",
                        dropShadow: {
                            enabled: !0,
                            blur: 8,
                            left: 1,
                            top: 1,
                            opacity: 0.2,
                        },
                        toolbar: {
                            show: !1
                        },
                        offsetY: 5,
                    },
                    series: [{
                            name: "Sales",
                            data: [90]
                        },
                        {
                            name: "Visit",
                            data: [70]
                        },
                    ],
                    stroke: {
                        width: 0
                    },
                    colors: [window.colors.solid.primary, window.colors.solid.info],
                    plotOptions: {
                        radar: {
                            polygons: {
                                strokeColors: [n, "transparent"],
                                connectorColors: "transparent",
                            },
                        },
                    },
                    fill: {
                        type: "gradient",
                        gradient: {
                            shade: "dark",
                            gradientToColors: [
                                window.colors.solid.primary,
                                window.colors.solid.info,
                            ],
                            shadeIntensity: 1,
                            type: "horizontal",
                            opacityFrom: 1,
                            opacityTo: 1,
                            stops: [0],
                        },
                    },
                    markers: {
                        size: 0
                    },
                    legend: {
                        show: !1
                    },
                    labels: ["Jan", "Feb"],
                    dataLabels: {
                        background: {
                            foreColor: [n]
                        }
                    },
                    yaxis: {
                        show: !1
                    },
                    grid: {
                        show: !1,
                        padding: {
                            bottom: -27
                        }
                    },
                }),
                new ApexCharts(c, a).render();


        }

        ring_chart(amount = 13);
    </script>
@endsection

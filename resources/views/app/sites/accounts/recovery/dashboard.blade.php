@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'dashboard') }}
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

        /* #apexchartsj0rxcjpl,
                                                                                                                                                                                #SvgjsSvg1119,
                                                                                                                                                                                .apexcharts-svg {
                                                                                                                                                                                    display: none;
                                                                                                                                                                                } */
    </style>
@endsection

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'dashboard') }}
@endsection

@section('content')
    <!-- Dashboard Analytics Start -->
    <section id="dashboard-analytics">
        <div class="row match-height">
            <!-- Greetings Card starts -->
            <div class="col-lg-6 col-md-12 col-sm-12">
                <div class="card card-congratulations">
                    <div class="card-body text-center">
                        <img src="{{ asset('app-assets') }}/images/elements/decore-left.png"
                            class="congratulations-img-left" alt="card-img-left" />
                        <img src="{{ asset('app-assets') }}/images/elements/decore-right.png"
                            class="congratulations-img-right" alt="card-img-right" />
                        <div class="avatar avatar-xl bg-primary shadow">
                            <div class="avatar-content">
                                <i data-feather="award" class="font-large-1"></i>
                            </div>
                        </div>
                        <div class="text-center">
                            <h1 class="mb-1 text-white">Congratulations John,</h1>
                            <p class="card-text m-auto w-75">
                                You have done <strong>57.6%</strong> more sales today. Check your new badge in your profile.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Greetings Card ends -->
            <!-- Subscribers Chart Card starts -->
            <div class="col-lg-3 col-sm-6 col-12">
                <div class="card">
                    <div class="card-header flex-column align-items-start pb-0">
                        <div class="avatar bg-light-primary p-50 m-0">
                            <div class="avatar-content">
                                <i data-feather="users" class="font-medium-5"></i>
                            </div>
                        </div>
                        <h2 class="fw-bolder mt-1">92.6k</h2>
                        <p class="card-text">Subscribers Gained</p>
                    </div>
                    <div id="gained-chart"></div>
                </div>
            </div>
            <!-- Subscribers Chart Card ends -->
            <!-- Orders Chart Card starts -->
            <div class="col-lg-3 col-sm-6 col-12">
                <div class="card">
                    <div class="card-header flex-column align-items-start pb-0">
                        <div class="avatar bg-light-warning p-50 m-0">
                            <div class="avatar-content">
                                <i data-feather="package" class="font-medium-5"></i>
                            </div>
                        </div>
                        <h2 class="fw-bolder mt-1">38.4K</h2>
                        <p class="card-text">Orders Received</p>
                    </div>
                    <div id="order-chart"></div>
                </div>
            </div>
            <!-- Orders Chart Card ends -->
        </div>
        <div class="row match-height">
            <!-- Avg Sessions Chart Card starts -->




            {{-- <________________________ next chart_______________________> --}}






            <div class="col-lg-6 col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row pb-50">
                            <div
                                class="col-sm-6 col-12 d-flex justify-content-between flex-column order-sm-1 order-2 mt-1 mt-sm-0">
                                <div class="mb-1 mb-sm-0">
                                    <h2 class="fw-bolder mb-25">2.7K</h2>
                                    <p class="card-text fw-bold mb-2">Avg Sessions</p>
                                    <div class="font-medium-2">
                                        <span class="text-success me-25">+5.2%</span>
                                        <span>vs last 7 days</span>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary">View Details</button>
                            </div>
                            <div
                                class="col-sm-6 col-12 d-flex justify-content-between flex-column text-end order-sm-2 order-1">
                                <div class="dropdown chart-dropdown">
                                    <button class="btn btn-sm border-0 dropdown-toggle p-50" type="button"
                                        id="dropdownItem5" data-bs-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                        Last 7 Days
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownItem5">
                                        <a class="dropdown-item" href="#">Last 28 Days</a>
                                        <a class="dropdown-item" href="#">Last Month</a>
                                        <a class="dropdown-item" href="#">Last Year</a>
                                    </div>
                                </div>
                                <div id="avg-sessions-chart"></div>
                            </div>
                        </div>
                        <hr />
                        <div class="row avg-sessions pt-50">
                            <div class="col-6 mb-2">
                                <p class="mb-50">Goal: $100000</p>
                                <div class="progress progress-bar-primary" style="height: 6px">
                                    <div class="progress-bar" role="progressbar" aria-valuenow="50" aria-valuemin="50"
                                        aria-valuemax="100" style="width: 50%"></div>
                                </div>
                            </div>
                            <div class="col-6 mb-2">
                                <p class="mb-50">Users: 100K</p>
                                <div class="progress progress-bar-warning" style="height: 6px">
                                    <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="60"
                                        aria-valuemax="100" style="width: 60%"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <p class="mb-50">Retention: 90%</p>
                                <div class="progress progress-bar-danger" style="height: 6px">
                                    <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="70"
                                        aria-valuemax="100" style="width: 70%"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <p class="mb-50">Duration: 1yr</p>
                                <div class="progress progress-bar-success" style="height: 6px">
                                    <div class="progress-bar" role="progressbar" aria-valuenow="90" aria-valuemin="90"
                                        aria-valuemax="100" style="width: 90%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Avg Sessions Chart Card ends -->
            <!-- Support Tracker Chart Card starts -->
            <div class="col-lg-6 col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between pb-0">
                        <h4 class="card-title">New Tracker</h4>
                        <div class="dropdown chart-dropdown">
                            <button class="btn btn-sm border-0 dropdown-toggle p-50" type="button" id="dropdownItem4"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Last Month
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownItem4">
                                <a class="dropdown-item" value="months1" id="months1" href="#">Month</a>
                                <a class="dropdown-item" value="months3" id="months3" href="#">3 Month</a>
                                <a class="dropdown-item" value="months6" id="months6" href="#">6 Month</a>
                                <a class="dropdown-item" value="months12" id="months12" href="#">Last Year</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-2 col-12 d-flex flex-column flex-wrap text-center">
                                <h1 class="font-large-2 fw-bolder mt-2 mb-0" id="installment_paid">163</h1>
                                <p class="card-text">Installment Paid</p>
                            </div>
                            <div class="col-sm-10 col-12 d-flex justify-content-center">
                                <div id="support-trackers-chart"></div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-1">
                            <div class="text-center">
                                <p class="card-text mb-50">Amount</p>
                                <span class="font-large-1 fw-bold" id="amount">29</span>
                            </div>
                            <div class="text-center">
                                <p class="card-text mb-50">Paid Amount</p>
                                <span class="font-large-1 fw-bold" id="paid_amount">63</span>
                            </div>
                            <div class="text-center">
                                <p class="card-text mb-50">Remaining Amount</p>
                                <span class="font-large-1 fw-bold" id="remaining_amount">1d</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Support Tracker Chart Card ends -->
        </div>
        <div class="row match-height">
            <!-- Timeline Card -->
            <div class="col-lg-4 col-12">
                <div class="card card-user-timeline">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <i data-feather="list" class="user-timeline-title-icon"></i>
                            <h4 class="card-title">User Timeline</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="timeline ms-50">
                            <li class="timeline-item">
                                <span class="timeline-point timeline-point-indicator"></span>
                                <div class="timeline-event">
                                    <h6>12 Invoices have been paid</h6>
                                    <p>Invoices are paid to the company</p>
                                    <div class="d-flex align-items-center">
                                        <img class="me-1" src="{{ asset('app-assets') }}/images/icons/json.png"
                                            alt="data.json" height="23" />
                                        <h6 class="more-info mb-0">data.json</h6>
                                    </div>
                                </div>
                            </li>
                            <li class="timeline-item">
                                <span class="timeline-point timeline-point-warning timeline-point-indicator"></span>
                                <div class="timeline-event">
                                    <h6>Client Meeting</h6>
                                    <p>Project meeting with Carl</p>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar me-50">
                                            <img src="{{ asset('app-assets') }}/images/portrait/small/avatar-s-9.jpg"
                                                alt="Avatar" width="38" height="38" />
                                        </div>
                                        <div class="more-info">
                                            <h6 class="mb-0">Carl Roy (Client)</h6>
                                            <p class="mb-0">CEO of Infibeam</p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="timeline-item">
                                <span class="timeline-point timeline-point-info timeline-point-indicator"></span>
                                <div class="timeline-event">
                                    <h6>Create a new project</h6>
                                    <p>Add files to new design folder</p>
                                    <div class="avatar-group">
                                        <div data-bs-toggle="tooltip" data-popup="tooltip-custom"
                                            data-bs-placement="bottom" title="Billy Hopkins" class="avatar pull-up">
                                            <img src="{{ asset('app-assets') }}/images/portrait/small/avatar-s-9.jpg"
                                                alt="Avatar" width="33" height="33" />
                                        </div>
                                        <div data-bs-toggle="tooltip" data-popup="tooltip-custom"
                                            data-bs-placement="bottom" title="Amy Carson" class="avatar pull-up">
                                            <img src="{{ asset('app-assets') }}/images/portrait/small/avatar-s-6.jpg"
                                                alt="Avatar" width="33" height="33" />
                                        </div>
                                        <div data-bs-toggle="tooltip" data-popup="tooltip-custom"
                                            data-bs-placement="bottom" title="Brandon Miles" class="avatar pull-up">
                                            <img src="{{ asset('app-assets') }}/images/portrait/small/avatar-s-8.jpg"
                                                alt="Avatar" width="33" height="33" />
                                        </div>
                                        <div data-bs-toggle="tooltip" data-popup="tooltip-custom"
                                            data-bs-placement="bottom" title="Daisy Weber" class="avatar pull-up">
                                            <img src="{{ asset('app-assets') }}/images/portrait/small/avatar-s-7.jpg"
                                                alt="Avatar" width="33" height="33" />
                                        </div>
                                        <div data-bs-toggle="tooltip" data-popup="tooltip-custom"
                                            data-bs-placement="bottom" title="Jenny Looper" class="avatar pull-up">
                                            <img src="{{ asset('app-assets') }}/images/portrait/small/avatar-s-20.jpg"
                                                alt="Avatar" width="33" height="33" />
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="timeline-item">
                                <span class="timeline-point timeline-point-danger timeline-point-indicator"></span>
                                <div class="timeline-event">
                                    <h6>Update project for client</h6>
                                    <p class="mb-0">Update files as per new design</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!--/ Timeline Card -->
            <!-- Sales Stats Chart Card starts -->
            <div class="col-lg-4 col-md-6 col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-start pb-1">
                        <div>
                            <h4 class="card-title mb-25">Sales</h4>
                            <p class="card-text">Last 6 months</p>
                        </div>
                        <div class="dropdown chart-dropdown">
                            <i data-feather="more-vertical" class="font-medium-3 cursor-pointer"
                                data-bs-toggle="dropdown"></i>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="#">Last 28 Days</a>
                                <a class="dropdown-item" href="#">Last Month</a>
                                <a class="dropdown-item" href="#">Last Year</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-inline-block me-1">
                            <div class="d-flex align-items-center">
                                <i data-feather="circle" class="font-small-3 text-primary me-50"></i>
                                <h6 class="mb-0">Sales</h6>
                            </div>
                        </div>
                        <div class="d-inline-block">
                            <div class="d-flex align-items-center">
                                <i data-feather="circle" class="font-small-3 text-info me-50"></i>
                                <h6 class="mb-0">Visits</h6>
                            </div>
                        </div>
                        <div id="sales-visit-chart" class="mt-50"></div>
                    </div>
                </div>
            </div>
            <!-- Sales Stats Chart Card ends -->
            <!-- App Design Card -->
            <div class="col-lg-4 col-md-6 col-12">
                <div class="card card-app-design">
                    <div class="card-body">
                        <span class="badge badge-light-primary">03 Sep, 20</span>
                        <h4 class="card-title mt-1 mb-75 pt-25">App design</h4>
                        <p class="card-text font-small-2 mb-2">
                            You can Find Only Post and Quotes Related to IOS like ipad app design, iphone app design
                        </p>
                        <div class="design-group mb-2 pt-50">
                            <h6 class="section-label">Team</h6>
                            <span class="badge badge-light-warning me-1">Figma</span>
                            <span class="badge badge-light-primary">Wireframe</span>
                        </div>
                        <div class="design-group pt-25">
                            <h6 class="section-label">Members</h6>
                            <div class="avatar">
                                <img src="{{ asset('app-assets') }}/images/portrait/small/avatar-s-9.jpg" width="34"
                                    height="34" alt="Avatar" />
                            </div>
                            <div class="avatar bg-light-danger">
                                <div class="avatar-content">PI</div>
                            </div>
                            <div class="avatar">
                                <img src="{{ asset('app-assets') }}/images/portrait/small/avatar-s-14.jpg" width="34"
                                    height="34" alt="Avatar" />
                            </div>
                            <div class="avatar">
                                <img src="{{ asset('app-assets') }}/images/portrait/small/avatar-s-7.jpg" width="34"
                                    height="34" alt="Avatar" />
                            </div>
                            <div class="avatar bg-light-secondary">
                                <div class="avatar-content">AL</div>
                            </div>
                        </div>
                        <div class="design-planning-wrapper mb-2 py-75">
                            <div class="design-planning">
                                <p class="card-text mb-25">Due Date</p>
                                <h6 class="mb-0">12 Apr, 21</h6>
                            </div>
                            <div class="design-planning">
                                <p class="card-text mb-25">Budget</p>
                                <h6 class="mb-0">$49251.91</h6>
                            </div>
                            <div class="design-planning">
                                <p class="card-text mb-25">Cost</p>
                                <h6 class="mb-0">$840.99</h6>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary w-100">Join Team</button>
                    </div>
                </div>
            </div>
            <!--/ App Design Card -->
        </div>
    </section>
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
            $("#months1,#months3,#months6,#months12").on('click', function(e) {

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
                            function numberWithCommas(num) {
                                return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                            }

                            // $('#amount').val(data.data.amount);
                            // $('#amount').html(
                            //     '<span class="font-large-1 fw-bold" id="amount">' +
                            //     numberWithCommas(data
                            //         .data.amount) + '</span>');
                            // $('#paid_amount').val(data.data.paid_amount);
                            // $('#paid_amount').html(
                            //     '<span class="font-large-1 fw-bold" id="amount">' +
                            //     numberWithCommas(data
                            //         .data.paid_amount) + '</span>');
                            // $('#remaining_amount').val(data.data.remaining_amount);
                            // $('#remaining_amount').html(
                            //     '<span class="font-large-1 fw-bold" id="amount">' +
                            //     numberWithCommas(data
                            //         .data.paid_amount) + '</span>');
                            // $('#installment_paid').html(
                            //     '<h1 class="font-large-2 fw-bolder mt-2 mb-0" id="installment_paid">' +
                            //     numberWithCommas(data
                            //         .data.installment_paid) + '</h1>');
                            // ring_chart(data.data.new_percentage);

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

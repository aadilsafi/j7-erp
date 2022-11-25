@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'dashboard') }}
@endsection

@section('page-title', 'Activity Logs')

@section('page-vendor')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/vendors/css/charts/apexcharts.css">
@endsection

@section('page-css')
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/pages/dashboard-ecommerce.min.css"> --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/plugins/charts/chart-apex.min.css">
@endsection

@section('custom-css')
    <style>
        .custom_card_heading {
            font-size: 1.3rem;
        }

        .activity_card {
            min-height: 28vh;

        }

        .custom_timeline {
            padding-bottom: 0.5rem !important;
        }

        .custom_card_scroll {
            overflow-y: scroll;
            max-height: 23vh;
        }

        .main_created .custom_card_heading {
            color: #0000ffb5;
        }

        .main_updated .custom_card_heading {
            color: #008000b5;
        }

        .main_deleted .custom_card_heading {
            color: #ff0000b5;
        }

        .custom_title_1 {
            margin-left: 1.8rem;
        }

        .custom_title_2 {
            margin-left: 3rem;
        }

        .custom_detail {
            margin-left: 2.5rem;
        }

        .custom_timeline {
            border: none !important;
        }

        @media(max-width: 991px) {
            .activity_card {
                height: 17vh !important;
            }
        }

        @media(max-width: 767px) {
            .activity_card {
                min-height: 18vh !important;
            }

            .custom_card_scroll {
                overflow-y: scroll;
                max-height: 15vh !important;
            }
        }

        @media(max-width: 1024px) {

            .activity_card {
                min-height: 19vh;
            }

            .custom_card_scroll {
                overflow-y: scroll;
                max-height: 17vh;
            }

            .custom_card_heading {
                font-size: 1rem;
            }
        }
    </style>
@endsection

@section('breadcrumbs')
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-start mb-0">Activity Logs</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('sites.settings.activity-logs.index', $site_id) }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')


    <section id="dashboard-analytics">

        <div class="row match-height">
            <!-- Timeline Card -->
            <div class="col-lg-12 col-12">
                <div class="card card-user-timeline">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <i data-feather="list" class="user-timeline-title-icon"></i>
                            <h4 class="card-title">Activity Logs</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- <ul class="timeline ms-50 "> -->
                        <div class="row">
                            {{-- <li class="timeline-item">
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
                </li> --}}
                            @foreach ($activityLogs as $log)
                                @if ($log->description == 'created')
                                    <div class="col-sm-12 col-xl-4 col-lg-6  custom_card_col main_created">
                                @endif
                                @if ($log->description == 'updated')
                                    <div class="col-sm-12 col-xl-4 col-lg-6  custom_card_col main_updated">
                                @endif
                                @if ($log->description == 'deleted')
                                    <div class="col-sm-12 col-xl-4 col-lg-6 custom_card_col main_deleted">
                                @endif
                                <!-- <li class="timeline-item"> -->
                                <div class="card activity_card"
                                    style="box-shadow: 0 4px 24px 0 rgb(34 41 47 / 10%) !important;">
                                    <div class="card-body">
                                        <span class="timeline-point timeline-point-primary timeline-point-indicator"></span>
                                        <div id="card_scroll" class="timeline-event custom_card_scroll">
                                            <ul class="timeline ms-50">
                                                <li class="timeline-item custom_timeline">
                                                    <span class="timeline-point timeline-point-indicator"></span>
                                                    <h6 class="custom_card_heading"> {{ $log->causer->name }} {{ ucfirst($log->description) }} {{ Str::replace('App\Models\\', ' ', $log->log_name) }}</h6>
                                                </li>

                                                <li class="timeline-item custom_timeline custom_title_2"
                                                    style="border: none !important;">
                                                    <span
                                                        class="timeline-point timeline-point-info timeline-point-indicator"></span>
                                                    <h6 class="custom_card_heading"> Details </h6>
                                                </li>
                                            </ul>

                                            <div class="d-flex align-items-center">
                                                {{-- <div class="avatar me-50">
                                                <img src="{{ asset('app-assets') }}/images/portrait/small/avatar-s-9.jpg"
                                            alt="Avatar" width="38" height="38" />
                                        </div> --}}

                                                <div class="more-info ps-5">

                                                    {{-- @dd(json_decode($log->properties)->attributes) --}}
                                                    @php
                                                        if (isset(json_decode($log->properties)->attributes)) {
                                                            $data = json_decode($log->properties)->attributes;
                                                        }
                                                        if (isset(json_decode($log->properties)->old)) {
                                                            $old = json_decode($log->properties)->old;
                                                        }
                                                    @endphp
                                                    @if (isset($data))
                                                        @foreach ($data as $key => $data)
                                                            @if ($key == 'site_id' ||
                                                                $key == 'password' ||
                                                                $key == 'slug' ||
                                                                // $key == 'status' ||
                                                                $key == 'OptionalCountryDetails' ||
                                                                $key == 'countryDetails'||
                                                                $key == 'stakeholder_data' ||
                                                                $key == 'country_id' ||
                                                                $key == 'state_id' ||
                                                                $key == 'city_id' ||
                                                                $key == 'stakeholder_data' ||
                                                                $key == 'is_imported'||
                                                                $key == 'created_date'||
                                                                $key == 'updated_date'||
                                                                $key == 'cancel'||
                                                                $key == 'created_at')
                                                                @continue
                                                            @endif
                                                            <p class="mb-0 custom_detail">{{ ucfirst($key) }} -
                                                                {{ ucfirst($data) }}
                                                            </p>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <!-- </li> -->
                        @endforeach
                        {{-- <li class="timeline-item">
                                <span class="timeline-point timeline-point-info timeline-point-indicator"></span>
                                <div class="timeline-event">
                                    <h6>Create a new project</h6>
                                    <p>Add files to new design folder</p>
                                    <div class="avatar-group">
                                        <div data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="bottom"
                                            title="Billy Hopkins" class="avatar pull-up">
                                            <img src="{{ asset('app-assets') }}/images/portrait/small/avatar-s-9.jpg"
                    alt="Avatar" width="33" height="33" />
                </div>
                <div data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="bottom" title="Amy Carson" class="avatar pull-up">
                    <img src="{{ asset('app-assets') }}/images/portrait/small/avatar-s-6.jpg" alt="Avatar" width="33" height="33" />
                </div>
                <div data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="bottom" title="Brandon Miles" class="avatar pull-up">
                    <img src="{{ asset('app-assets') }}/images/portrait/small/avatar-s-8.jpg" alt="Avatar" width="33" height="33" />
                </div>
                <div data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="bottom" title="Daisy Weber" class="avatar pull-up">
                    <img src="{{ asset('app-assets') }}/images/portrait/small/avatar-s-7.jpg" alt="Avatar" width="33" height="33" />
                </div>
                <div data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="bottom" title="Jenny Looper" class="avatar pull-up">
                    <img src="{{ asset('app-assets') }}/images/portrait/small/avatar-s-20.jpg" alt="Avatar" width="33" height="33" />
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
        </li> --}}
                        <!-- </ul> -->
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <nav aria-label="Page navigation example">
                                <ul class="pagination justify-content-center mt-2">

                                    <!-- {{ $activityLogs->currentPage() }} -->
                                    {{ $activityLogs->links() }}
                                    <!-- <li class="page-item prev-item"><a class="page-link" href="#"></a></li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item" aria-current="page"><a class="page-link" href="#">4</a></li>
                            <li class="page-item"><a class="page-link" href="#">5</a></li>
                            <li class="page-item"><a class="page-link" href="#">6</a></li>
                            <li class="page-item"><a class="page-link" href="#">7</a></li>
                            <li class="page-item next-item"><a class="page-link" href="#"></a></li> -->
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Timeline Card -->
        </div>

    </section>

@endsection

<!-- @section('vendor-js')
        <script src="{{ asset('app-assets') }}/vendors/js/charts/apexcharts.min.js"></script>
        <script src="{{ asset('app-assets') }}/vendors/js/extensions/toastr.min.js"></script>
        <script src="{{ asset('app-assets') }}/vendors/js/extensions/moment.min.js"></script> -->
@endsection

@section('page-js')
    <!-- <script src="{{ asset('app-assets') }}/js/scripts/pages/dashboard-analytics.min.js"></script> -->
    <!-- <script src="{{ asset('app-assets') }}/js/scripts/pages/app-invoice-list.min.js"></script> -->
@endsection

@section('custom-js')
@endsection

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
@endsection

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'dashboard') }}
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
                            <h4 class="card-title">Logs</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="timeline ms-50 ">
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
                                <li class="timeline-item">
                                    <span class="timeline-point timeline-point-primary timeline-point-indicator"></span>
                                    <div class="timeline-event">
                                        <h6>{{ $log->log_name }}</h6>
                                        <h6 class="ps-2">{{ $log->description }} </h6>
                                        <div class="d-flex align-items-center">
                                            {{-- <div class="avatar me-50">
                                                <img src="{{ asset('app-assets') }}/images/portrait/small/avatar-s-9.jpg"
                                                    alt="Avatar" width="38" height="38" />
                                            </div> --}}
                                            <div class="more-info">
                                                <h6 class="mb-0 ps-3">Details</h6>
                                                {{-- @dd(json_decode($log->properties)->attributes) --}}
                                                @php
                                                    $data = json_decode($log->properties)->attributes;
                                                @endphp
                                                @foreach ($data as $key=>$data)
                                                @if(
                                                    $key == 'site_id' ||
                                                    $key == 'password'||
                                                    $key == ''
                                                )
                                                    @continue
                                                @endif
                                                <p class="mb-0 ps-5">{{ $key  }} - {{ $data}}</p>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </li>
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
                                        <div data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="bottom"
                                            title="Amy Carson" class="avatar pull-up">
                                            <img src="{{ asset('app-assets') }}/images/portrait/small/avatar-s-6.jpg"
                                                alt="Avatar" width="33" height="33" />
                                        </div>
                                        <div data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="bottom"
                                            title="Brandon Miles" class="avatar pull-up">
                                            <img src="{{ asset('app-assets') }}/images/portrait/small/avatar-s-8.jpg"
                                                alt="Avatar" width="33" height="33" />
                                        </div>
                                        <div data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="bottom"
                                            title="Daisy Weber" class="avatar pull-up">
                                            <img src="{{ asset('app-assets') }}/images/portrait/small/avatar-s-7.jpg"
                                                alt="Avatar" width="33" height="33" />
                                        </div>
                                        <div data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="bottom"
                                            title="Jenny Looper" class="avatar pull-up">
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
                            </li> --}}
                        </ul>
                    </div>
                </div>
            </div>
            <!--/ Timeline Card -->
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
@endsection

@section('custom-js')
@endsection

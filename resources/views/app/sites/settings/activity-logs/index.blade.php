@extends('app.layout.layout') @section('seo-breadcrumb') {{ Breadcrumbs::view('breadcrumbs::json-ld', 'dashboard') }} @endsection @section('page-title', 'Timeline') @section('page-vendor')
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/vendors/css/charts/apexcharts.css"> @endsection @section('page-css') {{-- <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/pages/dashboard-ecommerce.min.css"> --}}
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/plugins/charts/chart-apex.min.css"> @endsection @section('custom-css') <style>
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
</style> @endsection @section('breadcrumbs') <div class="content-header-left col-md-9 col-12 mb-2">
  <div class="row breadcrumbs-top">
    <div class="col-12">
      <h2 class="content-header-title float-start mb-0">Activity Logs</h2>
      <div class="breadcrumb-wrapper">
        {{ Breadcrumbs::render('sites.settings.activity-logs.index', $site_id) }}
      </div>
    </div>
  </div>
</div> @endsection @section('content') <div class="content-body">
  <!-- Timeline Starts -->
  <section class="basic-timeline">
    <div class="row">
      <div class="col-md-6">
        <div class="col-12">
          <div class="card">
            <div class="card-header">

              <h4 class="card-title">Created</h4>
            </div> @foreach ($activityLogs as $log) @if ($log->description == 'updated') <div class="card-body">
              <ul class="timeline">
                <li class="timeline-item"></li>
                <li class="timeline-item">
                <span class="timeline-point timeline-point-secondary">
                    <i data-feather="user"></i>
                  </span>

                  <div class="timeline-event">
                    <div class="d-flex justify-content-between flex-sm-row flex-column mb-sm-0 mb-1">
                      <h6>Created By</h6>
                      <span class="timeline-event-time">{{ $log->created_at->diffForhumans() }}</span>
                    </div>
                    <div class="d-flex flex-row align-items-center">
                      {{-- <div class="avatar">
                        <img src="../../../app-assets/images/avatars/12-small.png" alt="avatar" height="38" width="38" />
                      </div> --}}
                      <div class="ms-50">
                        <h6 class="mb-0"> @if (isset($log->causer->name)) {{ $log->causer->name }} @endif {{ Str::replace('App\Models\\', ' ', $log->log_name) }}
                        </h6>
                      </div>
                    </div>
                  </div>
                </li>
                <li class="timeline-item">
              </ul>
            </div> @endif @endforeach
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4 class="card-title">Updated</h4>
            </div> @foreach ($activityLogs as $log) @if ($log->description == 'created') <div class="card-body">
              <ul class="timeline">
                <li class="timeline-item"></li>
                <li class="timeline-item">
                  <span class="timeline-point -point-secondary">
                    <i data-feather="user"></i>
                  </span>
                  <div class="timeline-event">
                    <div class="d-flex justify-content-between flex-sm-row flex-column mb-sm-0 mb-1">
                      <h6>Updated By</h6>
                      <span class="timeline-event-time">{{ $log->updated_at->diffForhumans() }}</span>
                    </div>
                    <div class="d-flex flex-row align-items-center">
                      {{-- <div class="avatar">
                        <img src="../../../app-assets/images/avatars/12-small.png" alt="avatar" height="38" width="38" />
                      </div> --}}
                      <div class="ms-50">
                        <h6 class="mb-0"> @if (isset($log->causer->name)) {{ $log->causer->name }} @endif {{ Str::replace('App\Models\\', ' ', $log->log_name) }}
                        </h6>
                      </div>
                    </div>
                  </div>
                </li>
                <li class="timeline-item">
                  <div class="timeline-event">
                    <p>Have to interview Katy Turner for the developer job.</p>
              </ul>

            </div> @endif @endforeach

          </div>
        </div>

      </div>
    </div>

  </section>
  <div class="text-end"></div>

  <!-- Timeline Ends -->
  <div class="text-center  mt-4 mb-3">
{{ $activityLogs->links() }}
  </div>
</div> @endsection

<!-- @section('vendor-js')
        <script src="{{ asset('app-assets') }}/vendors/js/charts/apexcharts.min.js"></script><script src="{{ asset('app-assets') }}/vendors/js/extensions/toastr.min.js"></script><script src="{{ asset('app-assets') }}/vendors/js/extensions/moment.min.js"></script> --> @endsection @section('page-js')
<!-- <script src="{{ asset('app-assets') }}/js/scripts/pages/dashboard-analytics.min.js"></script> -->
<!-- <script src="{{ asset('app-assets') }}/js/scripts/pages/app-invoice-list.min.js"></script> --> @endsection @section('custom-js') @endsection

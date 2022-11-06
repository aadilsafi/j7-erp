@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.accounts.trial-balance.index', encryptParams($site->id)) }}
@endsection

@section('page-title', 'Charts Of Accounts')

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
                <h2 class="content-header-title float-start mb-0">Trial Balance</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('sites.accounts.trial-balance.index', encryptParams($site->id)) }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')

    <section class="app-user-view-connections">
        <div class="row removeInvalidMessages">
            <div class="col-xl-12 col-lg-12">

                {{-- @dd($dataTable->table()); --}}
                {{-- <ul class="nav nav-pills mb-2">
                    <li class="nav-item">
                        <a class="nav-link active" id="salesPlanTab" data-bs-toggle="tab" href="#salesPlanData"
                            aria-controls="sales-plan" role="tab" aria-selected="true">
                            <i class="bi bi-receipt font-medium-3 me-50"></i>
                            <span class="fw-bold">Sales Invoice</span></a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" id="buyBackTab" data-bs-toggle="tab" href="#buybackData"
                            aria-controls="buy-back" role="tab" aria-selected="true">
                            <i data-feather="home" class="font-medium-3 me-50"></i>
                            <span class="fw-bold">Buy Back</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="refund" data-bs-toggle="tab" href="#refundData"
                            aria-controls="refund-data" role="tab" aria-selected="false">
                            <i data-feather="layers" class="font-medium-3 me-50"></i>
                            <span class="fw-bold">Refund</span></a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" id="resale" data-bs-toggle="tab" href="#resaleData"
                            aria-controls="resale-data" role="tab" aria-selected="false">
                            <i class="bi bi-receipt font-medium-3 me-50"></i>
                            <span class="fw-bold">Resale</span></a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" id="cancellation" data-bs-toggle="tab" href="#cancellationData"
                            aria-controls="cancellation-data" role="tab" aria-selected="false">
                            <i class="bi bi-door-open font-medium-3 me-50"></i>
                            <span class="fw-bold">Cancellation</span></a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" id="title-transfer" data-bs-toggle="tab" href="#titleTransferData"
                            aria-controls="title-transfer" role="tab" aria-selected="false">
                            <i class="bi bi-receipt font-medium-3 me-50"></i>
                            <span class="fw-bold">Title Transfer</span></a>
                    </li>
                </ul> --}}
                <div class="tab-content">
                    <div class="tab-pane active" id="salesPlanData" aria-labelledby="salesPlanData" role="tabpanel">

                        <div class="card">
                            <div class="card-body">
                                <form action="#" id="sales-invoice-table-form" method="get">
                                    {{ $dataTable->table() }}
                                </form>
                            </div>
                            <div>
                            </div>
                        </div>

                    </div>

                    <div class="tab-pane" id="buybackData" aria-labelledby="buybackData" role="tabpanel">

                        <div class="card">
                            <div class="card-body">
                                <form action="#" id="sales-invoice-table-form" method="get">
                                    {{ $dataTable->table() }}
                                </form>
                            </div>
                        </div>

                    </div>

                    <div class="tab-pane" id="refundData" aria-labelledby="refund" role="tabpanel">
                        <div class="card">
                            <div class="card-body">
                                <form action="#" id="sales-invoice-table-form" method="get">
                                    {{ $dataTable->table() }}
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane" id="resaleData" aria-labelledby="resale" role="tabpanel">
                        <div class="card">
                            <div class="card-body">
                                <form action="#" id="sales-invoice-table-form" method="get">
                                    {{ $dataTable->table() }}
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane" id="cancellationData" aria-labelledby="cancellation" role="tabpanel">
                        <div class="card">
                            <div class="card-body">
                                <form action="#" id="sales-invoice-table-form" method="get">
                                    {{ $dataTable->table() }}
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane" id="titleTransferData" aria-labelledby="title-transfer" role="tabpanel">
                        <div class="card">
                            <div class="card-body">
                                <form action="#" id="sales-invoice-table-form" method="get">
                                    {{ $dataTable->table() }}
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!--/ User Content -->
        </div>
    </section>
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
    {{ $dataTable->scripts() }}
    <script>
        $(".removeErrorMessage").on('click', function() {
            $('.invalid-feedback').empty();
            $('.invalid-tooltip').hide();
            $('.alert-danger').empty();
            $('.removeInvalidMessages').find('.is-invalid').removeClass("is-invalid");
        });

        var _token = '{{ csrf_token() }}';
        let url = "{{ route('sites.accounts.ledger.ajax-get-refund-datatable', ['site_id' => encryptParams($site->id)]) }}";

        // sites.accounts.charts-of-accounts.index
        // $.ajax({
        //     url: url,
        //     type: 'GET',
        //     dataType: 'json',
        //     success: function(data) {
        //         console.log(data);
        //     }
        // });
    </script>
@endsection

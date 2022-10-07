@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'dashboard') }}
@endsection

@section('page-title', 'Sales Plan')

@section('page-vendor')
@endsection

@section('page-css')
@endsection

@section('custom-css')
@endsection

@section('breadcrumbs')
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-start mb-0">Lead Sources</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('sites.lead-sources.index', $site_id) }}
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
                    <form action="javascript:void(0)" id="recovery-table-form" method="get">
                        <div class="table-responsive">
                            <table class="dt-complex-header table table-striped table-hover">
                                <thead>
                                    <tr class="text-center">
                                        <th rowspan="2">CHECK</th>
                                        <th rowspan="2">FLOORS</th>
                                        <th rowspan="2">UNITS</th>
                                        <th rowspan="2">Unit</th>
                                        <th rowspan="2">AREA</th>
                                        <th rowspan="2">SHORT LABEL</th>
                                        <th colspan="5">STATUSES</th>
                                        <th rowspan="2">CREATED AT</th>
                                        <th rowspan="2">UPDATED AT</th>
                                        <th rowspan="2" id="action">ACTIONS</th>
                                    </tr>
                                    <tr class="text-center">
                                        <th>OPEN</th>
                                        <th>SOLD</th>
                                        <th>TOKEN</th>
                                        <th>HOLD</th>
                                        <th>Partial Paid</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr class="text-center">
                                        <th rowspan="2">CHECK</th>
                                        <th rowspan="2">FLOORS</th>
                                        <th rowspan="2">ORDER</th>
                                        <th rowspan="2">AREA</th>
                                        <th rowspan="2">SHORT LABEL</th>
                                        <th rowspan="2">UNITS</th>
                                        <th>OPEN</th>
                                        <th>SOLD</th>
                                        <th>TOKEN</th>
                                        <th>HOLD</th>
                                        <th>Partial Paid</th>
                                        <th rowspan="2">CREATED AT</th>
                                        <th rowspan="2">UPDATED AT</th>
                                        <th rowspan="2">ACTIONS</th>
                                    </tr>
                                    <tr class="text-center">
                                        <th colspan="5">STATUSES</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('vendor-js')

@endsection

@section('page-js')

@endsection

@section('custom-js')
@endsection

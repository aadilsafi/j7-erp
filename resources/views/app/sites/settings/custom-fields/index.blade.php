@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.lead-sources.index', $site_id) }}
@endsection

@section('page-title', 'Custom Fields')

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
                <h2 class="content-header-title float-start mb-0">Custom Fields</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('sites.lead-sources.index', $site_id) }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('tab-content')
    <div class="tab-pane active" id="siteCongifData" aria-labelledby="site-congif-tab" role="tabpanel">
        <div class="card">
asdasdasdad
        </div>
    </div>
@endsection

@section('vendor-js')
@endsection

@section('page-js')
@endsection

@section('custom-js')

@endsection

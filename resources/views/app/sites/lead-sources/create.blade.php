@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.lead-sources.create', $site_id) }}
@endsection

@section('page-title', 'Create Lead Sources')

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
                <h2 class="content-header-title float-start mb-0">Create Lead Sources</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('sites.lead-sources.create', $site_id) }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <form class="form form-vertical" action="{{ route('sites.lead-sources.store', ['site_id' => encryptParams($site_id)]) }}"
            method="POST">

            <div class="card-header">
            </div>

            <div class="card-body">

                @csrf

                {{ view('app.sites.lead-sources.form-fields') }}

            </div>

            <div class="card-footer d-flex align-items-center justify-content-end">
                <button type="submit" class="btn btn-relief-outline-success waves-effect waves-float waves-light me-1">
                    <i data-feather='save'></i>
                    Save Lead Sources
                </button>
                <a href="{{ route('sites.lead-sources.index', ['site_id' => encryptParams($site_id)]) }}"
                    class="btn btn-relief-outline-danger waves-effect waves-float waves-light">
                    <i data-feather='x'></i>
                    {{ __('lang.commons.cancel') }}
                </a>
            </div>

        </form>
    </div>
@endsection

@section('vendor-js')
@endsection

@section('page-js')
@endsection

@section('custom-js')
@endsection

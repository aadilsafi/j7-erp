@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.floors.create', encryptParams($site_id)) }}
@endsection

@section('page-title', 'Create Floor')

@section('page-vendor')
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
                <h2 class="content-header-title float-start mb-0">Create Floor</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('sites.floors.create', encryptParams($site_id)) }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <form class="form form-vertical" action="{{ route('sites.floors.store', ['site_id' => encryptParams($site_id)]) }}" method="POST">

            <div class="card-header">
            </div>

            <div class="card-body">

                @csrf
                {{ view('app.sites.floors.form-fields', ['floorShortLable' => $floorShortLable, 'floorOrder' => $floorOrder]) }}

            </div>

            <div class="card-footer d-flex align-items-center justify-content-end">
                <button type="submit" class="btn btn-relief-outline-success waves-effect waves-float waves-light buttonToBlockUI me-1">
                    <i data-feather='save'></i>
                    Save Floor
                </button>
                <a href="{{ route('sites.floors.index', ['site_id' => encryptParams($site_id)]) }}"
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
    <script></script>
@endsection

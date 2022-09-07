@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.lead-sources.edit', $site_id) }}
@endsection

@section('page-title', 'Edit Lead Source')

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
                <h2 class="content-header-title float-start mb-0">Edit Lead Source</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('sites.lead-sources.edit', $site_id) }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <form action="{{ route('sites.lead-sources.update', ['site_id' => encryptParams($site_id), 'id' => encryptParams($leadSource->id)]) }}" method="POST">

            <div class="card-header">
            </div>

            <div class="card-body">

                @csrf
                @method('PUT')

                {{ view('app.sites.lead-sources.form-fields', ['leadSource' => $leadSource]) }}

            </div>

            <div class="card-footer d-flex align-items-center justify-content-end">
                <button type="submit" class="btn btn-relief-outline-success waves-effect waves-float waves-light me-1">
                    <i data-feather='save'></i>
                    Update Lead Source
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

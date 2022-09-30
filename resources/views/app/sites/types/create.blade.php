@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.types.create', $site_id) }}
@endsection

@section('page-title', 'Create Types')

@section('page-vendor')
    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets') }}/vendors/css/tables/datatable/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets') }}/vendors/css/tables/datatable/responsive.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets') }}/vendors/css/tables/datatable/buttons.bootstrap5.min.css">
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
                <h2 class="content-header-title float-start mb-0">Create Type</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('sites.types.create', $site_id) }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <form class="form form-vertical" action="{{ route('sites.types.store', ['site_id' => encryptParams($site_id)]) }}"
        method="POST">

        <div class="row">
            <div class="col-lg-9 col-md-9 col-sm-12 position-relative">

                @csrf
                {{ view('app.sites.types.form-fields', ['types' => $types]) }}

            </div>

            <div class="col-lg-3 col-md-3 col-sm-12 position-relative">
                <div class="sticky-md-top top-lg-100px top-md-100px top-sm-0px">
                    <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                        <div class="card-body">
                            <div class="row g-1">
                                <div class="col-md-12">
                                    <button type="submit"
                                        class="btn btn-relief-outline-success w-100 waves-effect waves-float waves-light me-1">
                                        <i data-feather='save'></i>
                                        Save Type
                                    </button>
                                </div>
                                <div class="col-md-12">
                                    <a href="{{ route('sites.types.index', ['site_id' => encryptParams($site_id)]) }}"
                                        class="btn btn-relief-outline-danger w-100 waves-effect waves-float waves-light">
                                        <i data-feather='x'></i>
                                        {{ __('lang.commons.cancel') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('vendor-js')
@endsection

@section('page-js')
@endsection

@section('custom-js')
    <script>
        function convertToSlug(text) {
            let slug = $('#type_slug');
            slug.val(text.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, ''));
        }
    </script>
@endsection

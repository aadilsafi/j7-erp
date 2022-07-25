@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.create') }}
@endsection

@section('page-title', 'Create Site')

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
                <h2 class="content-header-title float-start mb-0">Create Site</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('sites.create') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <form action="{{ route('sites.store') }}" method="POST">

            <div class="card-header">
            </div>

            <div class="card-body">

                @csrf

                {{ view('app.sites.form-fields', ['countries' => $countries]) }}

            </div>

            <div class="card-footer d-flex align-items-center justify-content-end">
                <button type="submit" class="btn btn-relief-outline-success waves-effect waves-float waves-light me-1">
                    <i data-feather='save'></i>
                    {{ __('lang.commons.save') }} Site
                </button>
                <a href="{{ route('sites.index') }}"
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
    <script>
        $(document).ready(function() {

            $("#countries").change(function() {
                let country = $("#countries").val();
                $.ajax({
                    url: "{{ url('/countries/cities') }}",
                    data: {
                        "country": country,
                    },
                    type: "get",
                    success: function(response) {
                        $.each(response, function(index, data) {
                            $("#city").append('<option value=' + data.id + '>' + data
                                .name + '</option>');
                        });
                    },
                });
            });
        });
    </script>
@endsection

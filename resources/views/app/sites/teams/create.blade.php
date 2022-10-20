@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.teams.create', $site_id) }}
@endsection

@section('page-title', 'Create Team')

@section('page-vendor')
    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets') }}/vendors/css/tables/datatable/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets') }}/vendors/css/tables/datatable/responsive.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets') }}/vendors/css/tables/datatable/buttons.bootstrap5.min.css">
        <link rel="stylesheet" type="text/css"
        href="{{ asset('public_assets/admin') }}/vendors/css/forms/select/select2.min.css">
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
                <h2 class="content-header-title float-start mb-0">Create Team</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('sites.teams.create', $site_id) }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
        <form class="form form-vertical" action="{{ route('sites.teams.store', ['site_id' => encryptParams($site_id)]) }}"
            method="POST">


            <div class="row">
                <div class="col-lg-9 col-md-9 col-sm-12 position-relative">

                    @csrf

                    {{ view('app.sites.teams.form-fields', ['teams' => $teams, 'users' => $users,  'customFields' => $customFields]) }}

                </div>

                <div class="col-lg-3 col-md-3 col-sm-12 position-relative">
                    <div class="sticky-md-top top-lg-100px top-md-100px top-sm-0px" style="z-index: auto">
                        <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                            <div class="card-body">
                                <div class="row g-1">
                                    <div class="col-md-12">
                                        <button type="submit"
                                            class="btn btn-relief-outline-success w-100 waves-effect waves-float waves-light buttonToBlockUI me-1">
                                            <i data-feather='save'></i>
                                            Save Team
                                        </button>
                                    </div>
                                    <div class="col-md-12">
                                        <a href="{{ route('sites.teams.index', ['site_id' => encryptParams($site_id)]) }}"
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
        $(document).ready(function() {
            $('#has_team').trigger('change');
            var e = $("#user_id");
            e.wrap('<div class="position-relative"></div>');
            e.select2({
                dropdownAutoWidth: !0,
                dropdownParent: e.parent(),
                width: "100%",
                containerCssClass: "select-lg",
            });
        });

        $('#has_team').on('change', function() {
            if ($(this).is(':checked')) {
                $('#hasChildCard').collapse('hide');
            } else {
                $('#hasChildCard').collapse('show');
            }
        });

        // $('#has_child').on('change', function() {
        //     if ($(this).is(':checked')) {
        //         $('#hasChildCard').hide();
        //     } else {
        //         $('#hasChildCard').show();
        //     }
        // });
    </script>
@endsection

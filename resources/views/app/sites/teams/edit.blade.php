@extends('app.layout.layout')

@section('seo-breadcrumb')
{{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.types.edit', $site_id) }}
@endsection

@section('page-title', 'Edit Team')

@section('page-vendor')
<link rel="stylesheet" type="text/css"
    href="{{ asset('app-assets') }}/vendors/css/tables/datatable/dataTables.bootstrap5.min.css">
<link rel="stylesheet" type="text/css"
    href="{{ asset('app-assets') }}/vendors/css/tables/datatable/responsive.bootstrap5.min.css">
<link rel="stylesheet" type="text/css"
    href="{{ asset('app-assets') }}/vendors/css/tables/datatable/buttons.bootstrap5.min.css">
<link rel="stylesheet" type="text/css"
    href="{{ asset('public_assets/admin') }}/vendors/css/forms/select/select2.min.css">I
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
            <h2 class="content-header-title float-start mb-0">Edit Team</h2>
            <div class="breadcrumb-wrapper">
                {{ Breadcrumbs::render('sites.teams.edit', $site_id) }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="card">
    <form
        action="{{ route('sites.teams.update', ['site_id' => encryptParams($site_id), 'id' => encryptParams($team->id)]) }}"
        method="POST">

        <div class="card-header">
        </div>

        <div class="card-body">

            @csrf
            @method('PUT')

            {{ view('app.sites.teams.form-fields', ['teams' => $teams, 'team' => $team, 'users'=>$users,
            'team_users'=>$team_users]) }}

        </div>

        <div class="card-footer d-flex align-items-center justify-content-end">
            <button type="submit" class="btn btn-relief-outline-success waves-effect waves-float waves-light me-1">
                <i data-feather='save'></i>
                Update Type
            </button>
            <a href="{{ route('sites.teams.index', ['site_id' => encryptParams($site_id)]) }}"
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
                $('#hasChildCard').hide();
            } else {
                $('#hasChildCard').show();
            }
        });
</script>
@endsection